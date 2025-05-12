<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Invoice extends Component
{
    public Order $order;

    #[Layout('layouts.guest')]
    public function render()
    {
        return view('livewire.customer.invoice');
    }
}
