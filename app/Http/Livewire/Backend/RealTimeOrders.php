<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Delivery\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class RealTimeOrders extends Component
{
    use WithPagination;

    public $totalOfOrders = 0;
    public $totalPriceOfOrders = 0;
    public $totalOfRequestedOrders = 0;
    public $totalOfAcceptedOrders = 0;
    public $totalOfDeliveredOrders = 0;
    public $totalOfCanceledOrders = 0;

    public $perPage = 10;

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
    }

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function render()
    {
        $recentOrders = Order::with(['customer.profile', 'merchant.profile', 'service'])
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.backend.real-time-orders', [
            'recentOrders' => $recentOrders
        ]);
    }
}
