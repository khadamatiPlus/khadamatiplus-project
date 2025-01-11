<?php
namespace App\Http\Livewire;
use App\Domains\Lookups\Models\City;
use Livewire\Component;
use Livewire\WithPagination;

class CityTable extends Component
{
    use WithPagination;

    public $search = '';

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

    public function render()
    {
        // Build query based on search input
        $cities = City::query()
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.cities-table', compact('cities'));
    }
}
