<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Wallet;
use Livewire\Component;

class AdminAnalytics extends Component
{
    public function render()
    {
        $orders = Order::query();
        $wallets = Wallet::query();
        $transactions = Transaction::query();
        $campaigns = Campaign::get(['price', 'start_date', 'end_date', 'status']);
        $averageCampaignDuration = $campaigns->avg(fn ($campaign) => $campaign->start_date->diffInDays($campaign->end_date));
        $averagePrice = $campaigns->avg('price');

        return view('livewire.admin.admin-analytics', compact('orders','wallets','transactions','campaigns','averageCampaignDuration','averagePrice'));
    }
}
