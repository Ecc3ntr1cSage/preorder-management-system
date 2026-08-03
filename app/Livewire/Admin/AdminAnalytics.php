<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Order;
use App\Models\Transaction;
use Livewire\Component;

class AdminAnalytics extends Component
{
    public function render()
    {
        $paidOrders = Order::query()->where('paid', true);
        $campaigns = Campaign::query()->get(['price', 'start_date', 'end_date', 'status']);
        $averageCampaignDuration = $campaigns->avg(fn ($campaign) => $campaign->start_date->diffInDays($campaign->end_date));
        $averagePrice = $campaigns->avg('price');
        $recentOrders = (clone $paidOrders)
            ->with('campaign')
            ->latest('paid_at')
            ->take(8)
            ->get();
        $pendingWithdrawals = Transaction::query()
            ->where('status', Transaction::STATUS_PENDING)
            ->with('wallet.user')
            ->latest()
            ->take(6)
            ->get();

        return view('livewire.admin.admin-analytics', [
            'totalSales' => (clone $paidOrders)->sum('amount'),
            'totalFees' => (clone $paidOrders)->sum('fee'),
            'totalShipping' => (clone $paidOrders)->sum('shipping'),
            'totalQuantity' => (clone $paidOrders)->sum('quantity'),
            'paidOrderCount' => (clone $paidOrders)->count(),
            'activeCampaignCount' => $campaigns->where('status', 1)->count(),
            'endedCampaignCount' => $campaigns->where('status', 2)->count(),
            'averageCampaignDuration' => $averageCampaignDuration,
            'averagePrice' => $averagePrice,
            'orderStatusCounts' => [
                0 => (clone $paidOrders)->where('status', 0)->count(),
                1 => (clone $paidOrders)->where('status', 1)->count(),
                2 => (clone $paidOrders)->where('status', 2)->count(),
                3 => (clone $paidOrders)->where('status', 3)->count(),
            ],
            'pendingWithdrawals' => $pendingWithdrawals,
            'pendingWithdrawalCount' => Transaction::query()->where('status', Transaction::STATUS_PENDING)->count(),
            'pendingWithdrawalAmount' => Transaction::query()->where('status', Transaction::STATUS_PENDING)->sum('withdrawn_amount'),
            'recentOrders' => $recentOrders,
        ]);
    }
}
