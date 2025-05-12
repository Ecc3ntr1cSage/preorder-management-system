<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

class History extends Component
{
    #[Layout('layouts.guest')]
    public function render()
    {
        $orders = Order::where('user_id', auth()->user()->id)->get();

        return view('livewire.customer.history', compact('orders'));
    }
}
