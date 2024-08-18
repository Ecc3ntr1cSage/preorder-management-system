<?php

use App\Http\Controllers\GoogleController;
use App\Http\Controllers\PaymentController;
use App\Livewire\Admin\AdminAnalytics;
use App\Livewire\Admin\AdminCampaign;
use App\Livewire\Admin\AdminCampaignInfo;
use App\Livewire\Admin\AdminOverview;
use App\Livewire\Admin\AdminSales;
use App\Livewire\Admin\AdminStorage;
use App\Livewire\Admin\AdminWallet;
use Illuminate\Support\Facades\Route;
use App\Livewire\User\Publish;
use App\Livewire\User\Manage;
use App\Livewire\User\Sales;
use App\Livewire\User\Invoice;
use App\Livewire\User\Info;
use App\Livewire\User\Payment;
use App\Livewire\User\Payout;
use App\Livewire\User\Show;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('main');
})->name('home');

Route::get('auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('callback/google', [GoogleController::class, 'callback'])->name('google.callback');

// Route::get('billplz-callback', [PaymentController::class, 'callback'])->name('billplz-callback');
// Route::get('billplz-redirect', [PaymentController::class, 'redirect'])->name('billplz-redirect');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'admin'])->group(function () {
    Route::get('admin/overview', AdminOverview::class)->name('admin.overview');
    Route::get('admin/analytics', AdminAnalytics::class)->name('admin.analytic');
    Route::get('admin/campaigns', AdminCampaign::class)->name('admin.campaign');
    Route::get('admin/sales', AdminSales::class)->name('admin.sale');
    Route::get('admin/wallets', AdminWallet::class)->name('admin.wallet');
    Route::get('admin/storage', AdminStorage::class)->name('admin.storage');
    Route::get('admin/campaigns/{campaign}', AdminCampaignInfo::class)->name('admin.campaign.info');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'user'])->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('dashboard/publish', Publish::class)->name('campaign.publish');
    Route::get('dashboard/campaigns', Manage::class)->name('campaign.manage');
    Route::get('dashboard/campaigns/{campaign}', Info::class)->name('campaign.info');
    Route::get('dashboard/my-sales', Sales::class)->name('campaign.sales');
    Route::get('dashboard/payouts', Payout::class)->name('payout');
});

Route::get('payment', Payment::class)->name('payment');
Route::get('{campaign}', Show::class)->name('campaign.show');
Route::get('order/{order}', Invoice::class)->name('invoice');
