<?php
namespace App\Http\Livewire;
use App\Domains\ContactUsSubmission\Models\ContactUsSubmission;
use App\Domains\Lookups\Models\Tag;
use Livewire\Component;
use Livewire\WithPagination;

class ContactUsSubmissionTable extends Component
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
        $contactUsSubmissions = ContactUsSubmission::query()
            ->where(function ($query) {
                $query->where('email', 'like', '%' . $this->search . '%')
                    ->orWhere('subject', 'like', '%' . $this->search . '%')
                    ->orWhere('message', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.contact-us-submissions-table', compact('contactUsSubmissions'));
    }
}
