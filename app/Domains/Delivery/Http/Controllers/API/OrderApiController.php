<?php

namespace App\Domains\Delivery\Http\Controllers\API;
use App\Domains\Customer\Models\Customer;
use App\Domains\Delivery\Http\Requests\API\ShowOrderRequest;
use App\Domains\Delivery\Http\Requests\API\StoreOrderAsMerchantRequest;
use App\Domains\Delivery\Http\Transformers\OrderTransformer;
use App\Domains\Delivery\Http\Transformers\RecentTransformer;
use App\Domains\Delivery\Models\Order;
use App\Domains\Delivery\Services\OrderService;
use App\Domains\Merchant\Models\MerchantAvailability;
use App\Domains\Service\Models\Service;
use App\Domains\Service\Models\ServiceOption;
use App\Http\Controllers\APIBaseController;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderApiController extends APIBaseController
{
    private OrderService $orderService;

    /**
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }



    public function requestOrder(Request $request,FirebaseNotificationService $firebaseService)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'time' => 'required',
        ]);

        // Get the authenticated customer_id
        $customerId = Auth::user()->customer_id;
        $customer = Customer::find($customerId);


        // Get the service and price
        $service = Service::find($validated['service_id']);
        $price = $service->price; // Assuming `price` is a column in the `services` table


        // Get the merchant device token from the service
        $merchant = $service->merchant; // Assuming the relationship between service and merchant
        $deviceToken = $merchant->profile->fcm_token; // Assuming `device_token` is stored in the merchant model
       // Create the order
        $order = Order::create([
            'customer_id' => $customerId,
            'service_id' => $validated['service_id'],
            'merchant_id' => $merchant->id,
            'price' => $price,
            'customer_phone' => $customer->defaultAddress->phone_number??"",
            'longitude' => $customer->defaultAddress->longitude??"",
            'latitude' => $customer->defaultAddress->latitude??"",
            'day' => $validated['day'],
            'time' => $validated['time'],
            'status' => 'pending',
            'customer_requested_at' => now(),
        ]);

        // Send a notification to the merchant
        if ($deviceToken) {
            $firebaseService->sendPushNotification(
                $deviceToken,
                "New Order Request",
                "You have a new order request for {$service->name} at {$validated['time']} on {$validated['day']}"
            );
        }

        return response()->json(['message' => 'Order created successfully.', 'order' => $order], 201);
    }


    public function updateOrderStatusByMerchant(Request $request, FirebaseNotificationService $firebaseService)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',  // Validate that the order exists
            'status' => 'required|in:accepted,on_the_way,on_progress,completed,cancelled', // Validate the status
            'options' => 'nullable|array', // Validate that options is an array (optional)
            'options.*' => 'exists:service_options,id' // Validate each option ID exists in service_options table
        ]);

        // Get the order from the database using the provided order_id
        $order = Order::find($validated['order_id']);

        // Check if the order exists
        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        // Check if the merchant is authorized to update the order
        if ($order->merchant_id != Auth::user()->merchant_id) {
            return response()->json(['message' => 'You are not authorized to update this order.'], 403);
        }

        // Initialize price change variable
        $priceChange = 0;

        // Check if options were provided and process them
        if (isset($validated['options']) && is_array($validated['options'])) {
            // Sync the selected options with the order
            $order->options()->sync($validated['options']);

            // Process each option and calculate its impact on price
            foreach ($validated['options'] as $optionId) {
                $option = ServiceOption::find($optionId);

                // Check if the option exists
                if ($option) {
                    // Determine how to modify the price based on option values
                    if ($option->value_type === 'increase') {
                        if ($option->type === 'fixed') {
                            $priceChange += $option->value; // Add fixed increase
                        } elseif ($option->type === 'percentage') {
                            $priceChange += ($order->price * ($option->value / 100)); // Add percentage increase
                        }
                    } elseif ($option->value_type === 'decrease') {
                        if ($option->type === 'fixed') {
                            $priceChange -= $option->value; // Subtract fixed decrease
                        } elseif ($option->type === 'percentage') {
                            $priceChange -= ($order->price * ($option->value / 100)); // Subtract percentage decrease
                        }
                    }
                }
            }
        }

        // Calculate the total price based on the base price and the price change
        $order->total_price = $order->price + $priceChange;
        $order->status = $validated['status'];
        $order->merchant_accepted_at = now();
        $order->save();

        // Get the customer and the merchant’s device token
        $customer = $order->customer;
        $deviceToken = $customer->profile->fcm_token;

        // Prepare the message for the notification
        $statusMessages = [
            'accepted' => "Your order for {$order->service->title} has been accepted.",
            'on_the_way' => "Your order for {$order->service->title} is on the way.",
            'on_progress' => "Your order for {$order->service->title} is in progress.",
            'completed' => "Your order for {$order->service->title} has been completed.",
            'cancelled' => "Your order for {$order->service->title} has been cancelled."
        ];

        // Send notification to the customer
        if ($deviceToken) {
            $firebaseService->sendPushNotification(
                $deviceToken,
                "Order Status Update",
                $statusMessages[$validated['status']]
            );
        }

        return response()->json(['message' => 'Order status updated successfully.', 'order' => $order], 200);
    }


    public function updateOrderStatusByCustomer(Request $request, FirebaseNotificationService $firebaseService)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',  // Validate that the order exists
            'status' => 'required|in:accepted,on_the_way,on_progress,completed,cancelled', // Validate the status
            'notes' => 'nullable' // Validate the status
        ]);

        // Get the order from the database using the provided order_id
        $order = Order::find($validated['order_id']);

        // Check if the order exists
        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        // Check if the merchant is authorized to update the order (you can customize this as needed)
        if ($order->customer_id != Auth::user()->customer_id) {
            return response()->json(['message' => 'You are not authorized to update this order.'], 403);
        }

        // Update the order status
        $order->status = $validated['status'];
        $order->notes = $validated['notes'];
        $order->save();

        // Get the customer and the merchant’s device token
        $merchant = $order->customer;
        $deviceToken = $merchant->profile->fcm_token;

        // Prepare the message for the notification
        $statusMessages = [
            'accepted' => "Your order for {$order->service->title} has been accepted.",
            'on_the_way' => "Your order for {$order->service->title} is on the way.",
            'on_progress' => "Your order for {$order->service->title} is in progress.",
            'completed' => "Your order for {$order->service->title} has been completed.",
            'cancelled' => "Your order for {$order->service->title} has been cancelled."
        ];

        // Send notification to the customer
        if ($deviceToken) {
            $firebaseService->sendPushNotification(
                $deviceToken,
                "Order Status Update",
                $statusMessages[$validated['status']]
            );
        }

        return response()->json(['message' => 'Order status updated successfully.', 'order' => $order], 200);
    }






    public function show(ShowOrderRequest $request): \Illuminate\Http\JsonResponse
    {
        if(auth()->user()->merchant) {
            $order = $this->orderService->getMerchantOrders(null, $request->input('order_id'))->first();
        }
        if(auth()->user()->captain) {
            $order = $this->orderService->getCaptainOrders(null, $request->input('order_id'))->first();
        }
        return $this->successResponse(!empty($order)?(new OrderTransformer)->transform($order):null);
    }


    public function getAllOrders(Request $request)
    {

        // Base query to get orders for the authenticated user (customer or merchant)
        $query = Order::query();

        // Check if the user is a customer or merchant and apply filters accordingly
        $user = Auth::user();

        // If the user is a merchant, only fetch orders for that merchant
        if (auth()->user()->merchant) {
            $query->where('merchant_id', $user->merchant_id);
        }

        // If the user is a customer, only fetch orders for that customer
        if (auth()->user()->customer) {
            $query->where('customer_id', $user->customer_id);
        }

        // Apply optional filter for order status if provided
        if ($request->has('status') && !empty($request->input('status'))) {
            $status = $request->input('status');
            $query->where('status', $status);
        }

        // Apply optional search filter for order search
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                    ->orWhere('service_title', 'like', "%{$search}%");
            });
        }

        // Order the results by created_at or another field if needed
        $query->orderBy('created_at', 'desc');

        // Paginate the orders (10 orders per page by default)
        $orders = $query->paginate(10);

        // Transform the paginated data (optional if you want to format the order data)


        $transformedOrders = $orders->getCollection()->map(function ($orders) {
            return (new OrderTransformer())->transform($orders);
        });

        // Return the response with paginated data and metadata
        return response()->json([
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




}
