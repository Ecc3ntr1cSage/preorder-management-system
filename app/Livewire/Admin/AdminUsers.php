<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Component;

class AdminUsers extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function toggleAdmin(int $userId): void
    {
        $user = User::where('is_admin', false)->findOrFail($userId);
        $user->update(['is_admin' => ! $user->is_admin]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $users = User::query()
            ->where('is_admin', false)
            ->when($this->search, fn ($query) => $query
                ->where(fn ($query) => $query
                    ->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")))
            ->withCount('campaigns')
            ->latest()
            ->paginate(12);

        return view('livewire.admin.admin-users', compact('users'));
    }
}
