<?php

namespace App\Livewire\Customer;

use App\Models\Campaign;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Shop extends Component
{

    #[Layout('layouts.guest')]
    public function render()
    {
        $campaigns = Campaign::with('images')->get();
        
        return view('livewire.customer.shop', compact('campaigns'));
    }
}
