<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Invoice extends Component
{
    public Order $order;

    public function mount(): void
    {
        abort_unless(
            auth()->user()->is_admin ||
            $this->order->user_id === auth()->id() ||
            $this->order->campaign?->user_id === auth()->id(),
            403
        );
    }

    #[Layout('layouts.guest')]
    public function render()
    {
        return view('livewire.customer.invoice');
    }
}
