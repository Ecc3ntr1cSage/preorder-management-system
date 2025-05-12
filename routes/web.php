<?php

// use App\Http\Controllers\GoogleController;
use App\Http\Controllers\PaymentController;
use App\Livewire\Admin\AdminAnalytics;
use App\Livewire\Admin\AdminCampaign;
use App\Livewire\Admin\AdminCampaignInfo;
use App\Livewire\Admin\AdminOverview;
use App\Livewire\Admin\AdminSales;
use App\Livewire\Admin\AdminStorage;
use App\Livewire\Admin\AdminWallet;
use App\Livewire\Business\Dashboard;
use App\Livewire\Business\Publish;
use App\Livewire\Business\Manage;
use App\Livewire\Business\Sales;
use App\Livewire\Business\Info;
use App\Livewire\Business\Payout;
use App\Livewire\Customer\Shop;
use App\Livewire\Customer\Show;
use App\Livewire\Customer\Payment;
use App\Livewire\Customer\Invoice;
use Illuminate\Support\Facades\Route;


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

// Route::get('auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
// Route::get('callback/google', [GoogleController::class, 'callback'])->name('google.callback');

Route::get('billplz-callback', [PaymentController::class, 'callback'])->name('billplz-callback');
Route::get('billplz-redirect', [PaymentController::class, 'redirect'])->name('billplz-redirect');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'role:0,1'])->group(function () {
    Route::get('admin/overview', AdminOverview::class)->name('admin.overview');
    Route::get('admin/analytics', AdminAnalytics::class)->name('admin.analytic');
    Route::get('admin/campaigns', AdminCampaign::class)->name('admin.campaign');
    Route::get('admin/sales', AdminSales::class)->name('admin.sale');
    Route::get('admin/wallets', AdminWallet::class)->name('admin.wallet');
    Route::get('admin/storage', AdminStorage::class)->name('admin.storage');
    Route::get('admin/campaigns/{campaign}', AdminCampaignInfo::class)->name('admin.campaign.info');
});


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'role:2'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('business.dashboard');
    Route::get('dashboard/publish', Publish::class)->name('business.publish');
    Route::get('dashboard/campaigns', Manage::class)->name('business.manage');
    Route::get('dashboard/campaigns/{campaign}', Info::class)->name('business.info');
    Route::get('dashboard/my-sales', Sales::class)->name('business.sales');
    Route::get('dashboard/payouts', Payout::class)->name('business.payout');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'role:3'])->group(function () {
    Route::get('shop', Shop::class)->name('customer.shop');
    Route::get('payment', Payment::class)->name('customer.payment');
    Route::get('order/{order}', Invoice::class)->name('customer.invoice');
});

Route::get('{campaign}', Show::class)->name('customer.show');
// Route::get('payment', Payment::class)->name('payment');
// Route::get('{campaign}', Show::class)->name('campaign.show');
// Route::get('order/{order}', Invoice::class)->name('invoice');
