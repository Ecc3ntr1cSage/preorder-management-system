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
        $users = User::where('role_id', 2)->paginate($this->perPage);
        
        return view('livewire.admin.admin-overview', compact('users'));
    }
}
