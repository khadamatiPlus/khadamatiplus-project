<?php
namespace App\Http\Livewire;
use App\Domains\ContactUsSubmission\Models\ContactUsSubmission;
use App\Domains\Lookups\Models\Tag;
use App\Domains\Subscriber\Models\Subscriber;
use Livewire\Component;
use Livewire\WithPagination;

class SubscriberTable extends Component
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
        $subscribers = Subscriber::query()
            ->where(function ($query) {
                $query->where('email', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.subscribers-table', compact('subscribers'));
    }
}
