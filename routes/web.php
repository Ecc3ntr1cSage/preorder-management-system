<?php

use App\Livewire\Admin\AdminAnalytics;
use App\Livewire\Admin\AdminCampaign;
use App\Livewire\Admin\AdminCampaignInfo;
use App\Livewire\Admin\AdminSales;
use App\Livewire\Admin\AdminUsers;
use App\Livewire\Admin\AdminWallet;
use App\Livewire\Business\Info;
use App\Livewire\Business\Manage;
use App\Livewire\Business\Payout;
use App\Livewire\Business\Publish;
use App\Livewire\Business\Sales;
use App\Livewire\Customer\History;
use App\Livewire\Customer\Invoice;
use App\Livewire\Customer\Payment;
use App\Livewire\Customer\Shop;
use App\Livewire\Customer\Show;
use App\Livewire\User\Dashboard;
use Illuminate\Support\Facades\Route;

Route::view('/', 'main')->name('home');
Route::redirect('register', '/')->name('register');
Route::get('shop', Shop::class)->name('customer.shop');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('dashboard', Dashboard::class)->name('dashboard');

    Route::get('campaigns/create', Publish::class)->name('business.publish');
    Route::get('campaigns', Manage::class)->name('business.manage');
    Route::get('campaigns/{campaign}', Info::class)->name('business.info');
    Route::get('my-sales', Sales::class)->name('business.sales');
    Route::get('payouts', Payout::class)->name('business.payout');

    Route::get('payment', Payment::class)->name('customer.payment');
    Route::get('order/{order}', Invoice::class)->name('customer.invoice');
    Route::get('past-orders', History::class)->name('customer.history');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function (): void {
    Route::get('overview', AdminAnalytics::class)->name('admin.overview');
    Route::redirect('analytics', '/admin/overview')->name('admin.analytic');
    Route::get('users', AdminUsers::class)->name('admin.users');
    Route::get('campaigns', AdminCampaign::class)->name('admin.campaign');
    Route::get('campaigns/{campaign}', AdminCampaignInfo::class)->name('admin.campaign.info');
    Route::get('sales', AdminSales::class)->name('admin.sale');
    Route::get('wallets', AdminWallet::class)->name('admin.wallet');
});

Route::get('{campaign}', Show::class)->name('customer.show');
