<?php
namespace App\Http\Livewire;
use App\Domains\Highlight\Models\Highlight;
use Livewire\Component;
use Livewire\WithPagination;

class HighlightTable extends Component
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
    public function toggleStatus($id)
    {
        $highlight = Highlight::findOrFail($id);
        $highlight->status = !$highlight->status; // Toggle status (0 to 1, or 1 to 0)
        $highlight->save();
    }
    public function render()
    {
        // Build query based on search input
        $highlights = Highlight::query()
            ->where(function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.highlights-table', compact('highlights'));
    }
}
