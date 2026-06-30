<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Rating\Models\Rating;
use Livewire\Component;
use Livewire\WithPagination;

class RatingsTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';

    public function render()
    {
        $query = Rating::with([
            'merchant.profile',
            'captain',
        ]);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('notes', 'like', '%' . $this->search . '%')
                    ->orWhereHas('merchant', function ($merchant) {
                        $merchant->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('captain', function ($captain) {
                        $captain->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $ratings = $query->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.backend.ratings-table', [
            'ratings' => $ratings
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
