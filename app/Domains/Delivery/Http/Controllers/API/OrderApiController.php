<?php

namespace App\Domains\Delivery\Http\Controllers\API;

use App\Domains\Customer\Models\Customer;
use App\Domains\Delivery\Http\Requests\API\RequestOrderRequest;
use App\Domains\Delivery\Http\Requests\API\ShowOrderRequest;
use App\Domains\Delivery\Http\Requests\API\UpdateOrderStatusByCustomerRequest;
use App\Domains\Delivery\Http\Requests\API\UpdateOrderStatusByMerchantRequest;
use App\Domains\Delivery\Http\Transformers\OrderTransformer;
use App\Domains\Delivery\Models\Order;
use App\Domains\Delivery\Services\OrderService;
use App\Exceptions\GeneralException;
use App\Http\Controllers\APIBaseController;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderApiController extends APIBaseController
{
    public function __construct(private OrderService $orderService)
    {
    }

    /**
     * Request Order
     *
     * Creates an order for an app service with selected variants.
     * All merchants linked to the app service receive a push notification.
     *
     * @group Orders
     * @authenticated
     *
     * @bodyParam app_service_id integer required The app service ID. Example: 1
     * @bodyParam variants object[] required Selected app service variants.
     * @bodyParam variants[].name string required Variant name. Example: Size
     * @bodyParam variants[].selected_options string[] required Selected option names. Example: ["Small"]
     * @bodyParam day string optional Booking day. Example: Monday
     * @bodyParam time string optional Booking time. Example: 10:00
     */
    public function requestOrder(RequestOrderRequest $request, FirebaseNotificationService $firebaseService)
    {
        $customer = Customer::findOrFail($request->user()->customer_id);
        $order = $this->orderService->createFromAppService($customer, $request->validated());
        $this->orderService->notifyLinkedMerchants($order, $firebaseService);

        return response()->json([
            'success' => true,
            'message' => __('Order created successfully.'),
            'data' => (new OrderTransformer())->transform(
                $order->load(['appService.category', 'appService.subCategory'])
            ),
        ], 201);
    }

    public function updateOrderStatusByMerchant(
        UpdateOrderStatusByMerchantRequest $request,
        FirebaseNotificationService $firebaseService
    ) {
        try {
            $order = Order::findOrFail($request->validated('order_id'));
            $order = $this->orderService->updateStatusByMerchant(
                $order,
                (int) Auth::user()->merchant_id,
                $request->validated('status')
            );
            $this->orderService->notifyCustomerAboutStatus($order, $request->validated('status'), $firebaseService);

            return $this->successResponse(
                (new OrderTransformer())->transform($order),
                __('Order status updated successfully.')
            );
        } catch (GeneralException $exception) {
            return $this->forbiddenErrorResponse($exception->getMessage());
        }
    }

    public function updateOrderStatusByCustomer(
        UpdateOrderStatusByCustomerRequest $request,
        FirebaseNotificationService $firebaseService
    ) {
        try {
            $order = Order::findOrFail($request->validated('order_id'));
            $order = $this->orderService->updateStatusByCustomer(
                $order,
                (int) Auth::user()->customer_id,
                $request->validated('status'),
                $request->validated('notes')
            );
            $this->orderService->notifyMerchantsAboutCustomerUpdate(
                $order,
                $request->validated('status'),
                $firebaseService
            );

            return $this->successResponse(
                (new OrderTransformer())->transform($order),
                __('Order status updated successfully.')
            );
        } catch (GeneralException $exception) {
            return $this->forbiddenErrorResponse($exception->getMessage());
        }
    }

    public function show(ShowOrderRequest $request): \Illuminate\Http\JsonResponse
    {
        $order = null;

        if (auth()->user()->merchant) {
            $order = $this->orderService
                ->getMerchantOrders(null, $request->input('order_id'))
                ->first();
        }

        if (auth()->user()->captain) {
            $order = $this->orderService
                ->getCaptainOrders(null, $request->input('order_id'))
                ->first();
        }

        return $this->successResponse(!empty($order) ? (new OrderTransformer)->transform($order) : null);
    }

    public function getAllOrders(Request $request)
    {
        $orders = $this->orderService
            ->buildOrdersQueryForAuthenticatedUser($request)
            ->paginate(10);

        $transformedOrders = $orders->getCollection()->map(
            fn (Order $order) => (new OrderTransformer())->transform($order)
        );

        return $this->successResponse([
            'data' => $transformedOrders,
            'pagination' => [
                'total' => $orders->total(),
                'count' => $orders->count(),
                'per_page' => $orders->perPage(),
                'current_page' => $orders->currentPage(),
                'total_pages' => $orders->lastPage(),
            ],
        ]);
    }

    public function getMonthlyOrderCounts(Request $request)
    {
        $user = Auth::user();

        if (!$user->merchant) {
            return $this->forbiddenErrorResponse(__('Unauthorized access. Only merchants can access this data.'));
        }

        $currentYear = now()->year;

        $orderCounts = Order::query()
            ->visibleToMerchant((int) $user->merchant_id)
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as order_count')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $monthlyOrderCounts = collect(range(1, 12))->map(function ($month) use ($orderCounts) {
            $order = $orderCounts->firstWhere('month', $month);

            return [
                'month' => $month,
                'order_count' => $order ? $order->order_count : 0,
            ];
        });

        return $this->successResponse([
            'data' => $monthlyOrderCounts,
            'year' => $currentYear,
        ], __('Monthly order counts retrieved successfully.'));
    }
}
