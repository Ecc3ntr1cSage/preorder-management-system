<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;

class AdminOverview extends Component
{
    public $perPage = 10;

    public function resetFilter()
    {
        $this->perPage = 10;
    }

    public function render()
    {
        $users = User::where('is_admin', false)->latest()->paginate($this->perPage);
        
        return view('livewire.admin.admin-overview', compact('users'));
    }
}
