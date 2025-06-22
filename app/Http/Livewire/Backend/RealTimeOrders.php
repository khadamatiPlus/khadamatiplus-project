<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Delivery\Models\Order;
use Livewire\Component;

class RealTimeOrders extends Component
{
    public $totalOfOrders = 0;
    public $totalPriceOfOrders = 0;
    public $totalOfRequestedOrders = 0;
    public $totalOfAcceptedOrders = 0;
    public $totalOfDeliveredOrders = 0;
    public $totalOfCanceledOrders = 0;
    public $recentOrders = [];

    protected $listeners = [
        'orderUpdated' => 'refreshData',
        'orderCreated' => 'refreshData'
    ];

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $this->totalOfOrders = Order::count();
        $this->totalPriceOfOrders = Order::sum('total_price') ?? 0;
        $this->totalOfRequestedOrders = Order::where('status', 'pending')->count();
        $this->totalOfAcceptedOrders = Order::where('status', 'accepted')->count();
        $this->totalOfDeliveredOrders = Order::where('status', 'completed')->count();
        $this->totalOfCanceledOrders = Order::where('status', 'cancelled')->count();
        
        $this->recentOrders = Order::with(['customer.profile', 'merchant.profile', 'service'])
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
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.backend.real-time-orders');
    }
} 