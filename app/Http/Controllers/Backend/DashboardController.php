<?php

namespace App\Http\Controllers\Backend;

use App\Domains\Delivery\Models\Order;
use App\Domains\Delivery\Services\OrderService;
use App\Enums\Core\OrderStatuses;

/**
 * Class DashboardController.
 */
class DashboardController
{
    public function __construct(OrderService $orderService){
        $this->orderService=$orderService;
    }
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $totalOfOrders = Order::count();
        $totalPriceOfOrders = Order::sum('total_price') ?? 0;
        $totalOfRequestedOrders = Order::where('status', 'pending')->count();
        $totalOfAcceptedOrders = Order::where('status', 'accepted')->count();
        $totalOfDeliveredOrders = Order::where('status', 'completed')->count();
        $totalOfCanceledOrders = Order::where('status', 'cancelled')->count();
        
        // Get recent orders for real-time updates with phone data
        $recentOrders = Order::with(['customer.profile', 'merchant.profile', 'service'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer' => [
                        'name' => $order->customer->name ?? 'N/A',
                        'phone' => $order->customer->profile->mobile_number ?? null,
                    ],
                    'merchant' => [
                        'name' => $order->merchant->name ?? 'N/A',
                        'phone' => $order->merchant->profile->mobile_number ?? null,
                    ],
                    'service' => [
                        'title' => $order->service->title ?? 'N/A',
                    ],
                    'status' => $order->status,
                    'price' => $order->price,
                    'total_price' => $order->total_price,
                    'customer_phone' => $order->customer_phone,
                    'created_at' => $order->created_at,
                ];
            });
        
        return view('backend.dashboard', compact(
            'totalOfOrders',
            'totalPriceOfOrders', 
            'totalOfRequestedOrders',
            'totalOfAcceptedOrders',
            'totalOfDeliveredOrders',
            'totalOfCanceledOrders',
            'recentOrders'
        ));
    }
}
