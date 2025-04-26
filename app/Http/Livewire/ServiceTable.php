<?php
namespace App\Http\Livewire;

use App\Domains\Service\Models\Service;
use App\Domains\Merchant\Models\Merchant;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceTable extends Component
{
    use WithPagination;

    public $search = '';
    public $merchantFilter = '';

    protected $paginationTheme = 'bootstrap';

    // Ensure search query string is synced with URL
    protected $queryString = [
        'search' => ['except' => ''],
        'merchantFilter' => ['except' => '']
    ];

    // Reset pagination when search input changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingMerchantFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $merchants = Merchant::orderBy('name')->get();

        // Build query based on search input
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
