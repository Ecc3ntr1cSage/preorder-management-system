<?php

namespace App\Livewire\User;

use App\Models\Campaign;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $user = Auth::user();

        return view('livewire.user.dashboard', [
            'campaigns' => Campaign::where('user_id', $user->id)->latest()->take(4)->get(),
            'orders' => Order::with('campaign')->where('user_id', $user->id)->latest('created_at')->take(4)->get(),
            'campaignCount' => Campaign::where('user_id', $user->id)->count(),
            'salesCount' => Order::whereHas('campaign', fn ($query) => $query->where('user_id', $user->id))->where('paid', true)->count(),
            'wallet' => $user->wallet,
        ]);
    }
}
