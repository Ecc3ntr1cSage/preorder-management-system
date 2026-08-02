<?php

namespace App\Livewire\Customer;

use App\Models\Campaign;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Shop extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    #[Layout('layouts.guest')]
    public function render()
    {
        $campaigns = Campaign::with('images')
            ->where('status', 1)
            ->when($this->search, fn ($query) => $query->where('title', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(9);
        
        return view('livewire.customer.shop', compact('campaigns'));
    }
}
