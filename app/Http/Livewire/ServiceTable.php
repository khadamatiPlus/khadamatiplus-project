<?php

namespace App\Http\Livewire;

use App\Domains\Service\Models\Service;
use App\Domains\Merchant\Models\Merchant;
use App\Exports\ServicesExport;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ServiceTable extends Component
{
    use WithPagination;

    public $search = '';
    public $merchantFilter = '';

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'merchantFilter' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingMerchantFilter()
    {
        $this->resetPage();
    }

    public function export()
    {
        $services = Service::with(['merchant.profile', 'reviews', 'tags'])
            ->when($this->merchantFilter != '', function ($query) {
                $query->where('merchant_id', $this->merchantFilter);
            })
            ->when($this->search != '', function ($query) {
                $query->where(function($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhereHas('merchant', function($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->get();

        $filename = 'services_' . now()->format('Y_m_d_His') . '.xlsx';

        return Excel::download(new ServicesExport($services), $filename);
    }

    public function render()
    {
        $merchants = Merchant::orderBy('name')->get();

        $services = Service::query()
            ->with(['merchant', 'merchant.profile'])
            ->where(function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhereHas('merchant', function($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhereHas('profile', function($q2) {
                                $q2->where('mobile_number', 'like', '%' . $this->search . '%');
                            });
                    });
            })
            ->when($this->merchantFilter, function ($query) {
                $query->where('merchant_id', $this->merchantFilter);
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.services-table', compact('services', 'merchants'));
    }
}
