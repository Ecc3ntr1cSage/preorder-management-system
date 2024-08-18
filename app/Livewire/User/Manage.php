<?php

namespace App\Livewire\User;

use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Manage extends Component
{
    
    public function render()
    {
        $campaigns = Campaign::with('images')->where('user_id', Auth::user()->id)->get();

        return view('livewire.user.manage', compact('campaigns'));
    }
}
