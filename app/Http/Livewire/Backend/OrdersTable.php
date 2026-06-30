<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Delivery\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $statusFilter = '';

    protected $listeners = [
        'orderUpdated' => '$refresh',
        'orderCreated' => '$refresh'
    ];

    public function render()
    {
        $query = Order::with([
            'customer.profile',
            'merchant.profile',
            'appService',
            'service',
        ]);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_reference', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($customer) {
                        $customer->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('merchant', function ($merchant) {
                        $merchant->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.backend.orders-table', [
            'orders' => $orders
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
}
