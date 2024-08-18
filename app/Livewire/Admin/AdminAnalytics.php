<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminAnalytics extends Component
{
    public function render()
    {
        $orders = Order::select('amount','fee','discount','quantity','shipping');
        $wallets = Wallet::select('earning','balance');
        $transactions = Transaction::select('withdrawn_amount');
              $averageCampaignDuration = Campaign::select(DB::raw('AVG(DATEDIFF(end_date, start_date)) AS average_duration'))
        ->value('average_duration');
        $campaigns = Campaign::select('price','start_date','end_date');
  
        $averagePrice = $campaigns->avg('price');

        return view('livewire.admin.admin-analytics', compact('orders','wallets','transactions','campaigns','averageCampaignDuration','averagePrice'));
    }
}
