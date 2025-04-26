<?php
namespace App\Http\Livewire;

use App\Domains\Merchant\Models\Merchant;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MerchantsExport;

class MerchantTable extends Component
{
    use WithPagination;

    public $search = '';
    public $exportStartDate;
    public $exportEndDate;

    protected $paginationTheme = 'bootstrap';

    // Ensure search query string is synced with URL
    protected $queryString = [
        'search' => ['except' => '']
    ];

    // Reset pagination when search input changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function export()
    {
        $query = $this->getFilteredQuery();

        return Excel::download(
            new MerchantsExport($query->get()),
            'merchants_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    protected function getFilteredQuery()
    {
        return Merchant::query()
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('profile', function($q) {
                        $q->where('mobile_number', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->exportStartDate, function ($query) {
                $query->whereDate('created_at', '>=', $this->exportStartDate);
            })
            ->when($this->exportEndDate, function ($query) {
                $query->whereDate('created_at', '<=', $this->exportEndDate);
            });
    }

    public function render()
    {
        $merchants = $this->getFilteredQuery()->paginate(10);

        return view('livewire.merchants-table', compact('merchants'));
    }
}
