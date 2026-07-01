<?php

namespace App\Domains\Delivery\Services;


use App\Domains\AppService\Models\AppService;
use App\Domains\Customer\Models\Customer;
use App\Domains\Delivery\Events\Order\OrderMarkedCompleted;
use App\Domains\Delivery\Models\Order;
use App\Domains\Merchant\Models\Merchant;
use App\Domains\Merchant\Services\MerchantBranchService;
use App\Domains\Merchant\Services\MerchantService;
use App\Enums\Core\CaptainStatuses;
use App\Enums\Core\ErrorTypes;
use App\Enums\Core\OrderStatuses;
use App\Exceptions\GeneralException;
use App\Services\BaseService;
use App\Services\FirebaseNotificationService;
use App\Services\StorageManagerService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService extends BaseService
{
    /**
     * @var string $entityName
     */
    protected $entityName = 'Order';


    /**
     * @var MerchantService
     */
    protected $merchantService;

    /**
     * @param Order $order
     * @param MerchantService $merchantService
     */
    public function __construct(
        Order $order,
        MerchantService $merchantService,
        StorageManagerService $storageManagerService,
        private AppServiceVariantResolver $variantResolver
    ) {
        $this->model = $order;
        $this->merchantService = $merchantService;
        $this->storageManagerService = $storageManagerService;
    }

    public function createFromAppService(Customer $customer, array $data): Order
    {
        $appService = AppService::query()
            ->where('id', $data['app_service_id'])
            ->where('status', 'active')
            ->firstOrFail();

        $resolved = $this->variantResolver->resolve($appService, $data['variants']);

        $totalPrice = $resolved['total_price'];
        $couponId = null;

        // Apply coupon discount if provided
        if (!empty($data['coupon_code'])) {
            $coupon = \App\Domains\Coupon\Models\Coupon::query()
                ->where('code', $data['coupon_code'])
                ->active()
                ->valid()
                ->notExpired()
                ->first();

            if ($coupon) {
                $couponId = $coupon->id;

                // Calculate discount based on coupon type
                if ($coupon->discount_type === 'percentage') {
                    $discountAmount = ($totalPrice * $coupon->discount_value) / 100;
                } else {
                    $discountAmount = $coupon->discount_value;
                }

                // Apply maximum discount limit if set
                if ($coupon->maximum_discount_amount && $discountAmount > $coupon->maximum_discount_amount) {
                    $discountAmount = $coupon->maximum_discount_amount;
                }

                $totalPrice = max(0, $totalPrice - $discountAmount);

                // Increment coupon usage
                $coupon->incrementUsage();
            }
        }

        return Order::create([
            'customer_id' => $customer->id,
            'app_service_id' => $appService->id,
            'selected_variants' => $resolved['selected_variants'],
            'merchant_id' => null,
            'price' => $resolved['price'],
            'total_price' => $totalPrice,
            'payment_method' => $data['payment_method'] ?? null,
            'coupon_id' => $couponId,
            'customer_phone' => $customer->defaultAddress->phone_number ?? '',
            'longitude' => $customer->defaultAddress->longitude ?? '',
            'latitude' => $customer->defaultAddress->latitude ?? '',
            'day' => $data['day'] ?? now()->format('l'),
            'time' => $data['time'] ?? now()->format('H:i'),
            'status' => 'pending',
            'customer_requested_at' => now(),
        ]);
    }

    public function notifyLinkedMerchants(Order $order, FirebaseNotificationService $firebaseService): int
    {
        if (!$order->app_service_id) {
            return 0;
        }

        $tokens = Merchant::query()
            ->whereHas('appServices', fn (Builder $query) => $query->where('app_services.id', $order->app_service_id))
            ->with('profile')
            ->get()
            ->pluck('profile.fcm_token')
            ->all();

        return $firebaseService->sendPushNotificationToMany(
            $tokens,
            __('New order'),
            __('You have a new order for :service', ['service' => $order->getDisplayName()])
        );
    }

    public function merchantCanManageOrder(Order $order, int $merchantId): bool
    {
        if ($order->merchant_id !== null) {
            return (int) $order->merchant_id === $merchantId;
        }

        if ($order->status !== 'pending' || !$order->app_service_id) {
            return false;
        }

        return Merchant::query()
            ->where('id', $merchantId)
            ->whereHas('appServices', fn (Builder $query) => $query->where('app_services.id', $order->app_service_id))
            ->exists();
    }

    public function updateStatusByMerchant(Order $order, int $merchantId, string $status): Order
    {
        if (!$this->merchantCanManageOrder($order, $merchantId)) {
            throw new GeneralException(__('You are not authorized to update this order.'));
        }

        return DB::transaction(function () use ($order, $merchantId, $status) {
            $order = Order::query()->lockForUpdate()->findOrFail($order->id);

            if ($order->merchant_id !== null && (int) $order->merchant_id !== $merchantId) {
                throw new GeneralException(__('This order has already been assigned to another merchant.'));
            }

            if ($order->merchant_id === null && $status === 'accepted') {
                $order->merchant_id = $merchantId;
                $order->merchant_accepted_at = now();
            } elseif ($status === 'accepted' && !$order->merchant_accepted_at) {
                $order->merchant_accepted_at = now();
            }

            $order->status = $status;
            $order->save();

            return $order->fresh(['appService.category', 'appService.subCategory', 'merchant.profile', 'customer.profile']);
        });
    }

    public function updateStatusByCustomer(Order $order, int $customerId, string $status, ?string $notes = null): Order
    {
        if ((int) $order->customer_id !== $customerId) {
            throw new GeneralException(__('You are not authorized to update this order.'));
        }

        $order->status = $status;
        $order->notes = $notes;
        $order->save();

        return $order->fresh(['appService', 'merchant.profile', 'customer.profile']);
    }

    public function notifyCustomerAboutStatus(Order $order, string $status, FirebaseNotificationService $firebaseService): void
    {
        $deviceToken = $order->customer?->profile?->fcm_token;
        $serviceName = $order->getDisplayName();

        $statusMessages = [
            'accepted' => __('Your order for :service has been accepted.', ['service' => $serviceName]),
            'on_the_way' => __('Your order for :service is on the way.', ['service' => $serviceName]),
            'on_progress' => __('Your order for :service is in progress.', ['service' => $serviceName]),
            'completed' => __('Your order for :service has been completed.', ['service' => $serviceName]),
            'cancelled' => __('Your order for :service has been cancelled.', ['service' => $serviceName]),
        ];

        if ($deviceToken && isset($statusMessages[$status])) {
            $firebaseService->sendPushNotification(
                $deviceToken,
                __('Order status update'),
                $statusMessages[$status]
            );
        }
    }

    public function notifyMerchantsAboutCustomerUpdate(Order $order, string $status, FirebaseNotificationService $firebaseService): int
    {
        $serviceName = $order->getDisplayName();
        $message = match ($status) {
            'cancelled' => __('Order for :service was cancelled by the customer.', ['service' => $serviceName]),
            default => __('Customer updated order for :service to :status.', [
                'service' => $serviceName,
                'status' => $status,
            ]),
        };

        if ($order->merchant_id) {
            $token = $order->merchant?->profile?->fcm_token;

            return $firebaseService->sendPushNotificationToMany(
                [$token],
                __('Order status update'),
                $message
            );
        }

        if (!$order->app_service_id) {
            return 0;
        }

        $tokens = Merchant::query()
            ->whereHas('appServices', fn (Builder $query) => $query->where('app_services.id', $order->app_service_id))
            ->with('profile')
            ->get()
            ->pluck('profile.fcm_token')
            ->all();

        return $firebaseService->sendPushNotificationToMany(
            $tokens,
            __('Order status update'),
            $message
        );
    }

    public function buildOrdersQueryForAuthenticatedUser(Request $request): Builder
    {
        $query = Order::query()->with([
            'appService.category',
            'appService.subCategory',
            'service',
            'merchant.profile',
            'customer.profile',
        ]);

        $user = Auth::user();

        if ($user?->merchant_id) {
            $query->visibleToMerchant((int) $user->merchant_id);
        }

        if ($user?->customer_id) {
            $query->where('customer_id', $user->customer_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function (Builder $query) use ($search) {
                $query->where('id', 'like', "%{$search}%")
                    ->orWhereHas('appService', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('service', fn (Builder $query) => $query->where('title', 'like', "%{$search}%"));
            });
        }

        return $query->orderByDesc('created_at');
    }


    /**
     * @param array $data
     * @return mixed
     * @throws GeneralException
     * @throws \Throwable
     */
    public function addMerchantOrder(array $data = [])
    {
        $orderNumber = Str::upper('ORD' . uniqid());
        $user = auth()->user();
        $data['order_reference'] = $orderNumber;
        $data['merchant_id'] = $user->merchant_id;
        if($data['is_instant_delivery']==true){
            $data['is_instant_delivery']=1;
        }
        if(!empty($data['voice_record']) && request()->hasFile('voice_record')){
            try {
                $this->upload($data,'voice_record');
            } catch (\Exception $e) {
                throw $e;
            }
        }
        $order = parent::store($data);
        return $order;
    }

    public function getMerchantOrders($status = [],$orderId = null)
    {
        return $this->model::query()
            ->with(['appService', 'service', 'merchant.profile', 'customer.profile'])
            ->visibleToMerchant((int) auth()->user()->merchant_id)
            ->when($status, fn ($query, $status) => $query->whereIn('status', $status))
            ->when($orderId, fn ($query, $orderId) => $query->where('id', $orderId))
            ->orderByDesc('id');
    }
    public function getCaptainOrders($from_date = null,$to_date = null)
    {
        $query=  $this->model::query()->
        when(auth()->user()->captain_id, fn($query, $captain_id) => $query->where('captain_id', $captain_id));
        if (!empty($from_date) && !empty($to_date)) {
            $from_date = Carbon::parse($from_date)->startOfDay();
            $to_date = Carbon::parse($to_date)->endOfDay();
            $query->whereBetween('delivered_at', [$from_date, $to_date]);
        }
        return $query->orderBy('created_at','desc');
    }
    /**
     * @param array $data
     * @return array
     */
    public function merchantAction($data = []): array
    {
        $order = Recent::query()
            ->when(auth()->user()->merchant_id,fn($query, $merchant_id) => $query->where('merchant_id', $merchant_id))
            ->when(auth()->user()->merchant_branch_id,fn($query, $merchant_branch_id) => $query->where('merchant_branch_id', $merchant_branch_id))
            ->when($data['order_id'], fn ($query, $orderId) => $query->where('id', $orderId))
            ->first();

        if(empty($order)){
            return [
                'done' => false,
                'message' => __('Order not found')
            ];
        }
        switch (true)
        {
            case $data['action_id'] == OrderActions::MERCHANT_ACCEPT_ORDER:
                if($order->status == OrderStatuses::NEW_ORDER){
                    $order->acceptByMerchant();
                    $resp = [
                        'done' => true,
                        'message' => __('The order has been accepted')
                    ];
                }
                else{
                    $resp = [
                        'done' => false,
                        'message' => __('You already accepted this order')
                    ];
                }
                break;
            case $data['action_id'] == OrderActions::MERCHANT_CONFIRM_CAPTAIN_ARRIVAL:
                if($order->status == OrderStatuses::CAPTAIN_ACCEPTED){
                    $order->confirmCaptainArrival();
                    $resp = [
                        'done' => true,
                        'message' => __('Confirmed captain arrival successfully done')
                    ];
                }
                else{
                    $resp = [
                        'done' => false,
                        'message' => __('You already confirmed that the captain arrived')
                    ];
                }
                break;
            case $data['action_id'] == OrderActions::MERCHANT_REJECT_ORDER:
            default:
                if($order->status == OrderStatuses::NEW_ORDER){
                    $order->rejectByMerchant();
                    $resp = [
                        'done' => true,
                        'message' => __('The order has been rejected')
                    ];
                }
                else{
                    $resp = [
                        'done' => false,
                        'message' => __('You already rejected this order')
                    ];
                }
                break;
        }
        return $resp;
    }
    protected function internalServerErrorResponse($error, array $responseHeaders = []): \Illuminate\Http\JsonResponse
    {
        return response()->json(['error_type' => ErrorTypes::GENERAL, 'errors' => [array('key' => 'general', 'error' => $error)]], 500, $responseHeaders, JSON_UNESCAPED_SLASHES);
    }
    private function upload(array &$data, $fileColumn, string $directory = 'order/voice_records'): array
    {
        try{
            $data[$fileColumn] = $this->storageManagerService->uploadPublicFile($data[$fileColumn],$directory);
            return $data;
        }
        catch (\Exception $exception){
            throw $exception;
        }
    }

}
