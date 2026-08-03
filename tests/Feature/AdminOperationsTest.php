<?php

namespace Tests\Feature;

use App\Livewire\Admin\AdminAnalytics;
use App\Livewire\Admin\AdminCampaign;
use App\Livewire\Admin\AdminCampaignInfo;
use App\Livewire\Admin\AdminSales;
use App\Livewire\Admin\AdminWallet;
use App\Models\Campaign;
use App\Models\Question;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminOperationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_routes_are_protected_and_analytics_redirects(): void
    {
        $this->get('/admin/overview')->assertRedirect('/login');

        $member = User::factory()->create();
        $this->actingAs($member)->get('/admin/overview')->assertForbidden();

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get('/admin/overview')->assertOk();
        $this->actingAs($admin)->get('/admin/analytics')->assertRedirect('/admin/overview');

        $campaign = $this->campaign(User::factory()->create(), 'Route campaign');
        foreach (['/admin/users', '/admin/campaigns', '/admin/sales', '/admin/wallets', '/admin/campaigns/'.$campaign->slug] as $path) {
            $this->actingAs($admin)->get($path)->assertOk();
        }
    }

    public function test_overview_and_campaign_sales_components_render_their_operational_surfaces(): void
    {
        $owner = User::factory()->create();
        $campaign = $this->campaign($owner, 'Ledger campaign');
        $order = $campaign->orders()->create([
            'collection_id' => 'test',
            'user_id' => User::factory()->create()->id,
            'email' => 'buyer@example.com',
            'name' => 'Buyer',
            'phone' => '0123456789',
            'status' => 1,
            'amount' => 5000,
            'discount' => 0,
            'quantity' => 1,
            'fee' => 150,
            'shipping' => 0,
            'variations' => 'Standard',
            'paid' => true,
            'paid_at' => now(),
            'address' => '1 Test Street',
            'postcode' => '50000',
            'state' => 'Selangor',
        ]);

        Livewire::test(AdminAnalytics::class)->assertSee('Market pulse')->assertSee('Ledger campaign');
        Livewire::test(AdminCampaign::class)->set('search', 'Ledger')->assertSee('Ledger campaign');
        Livewire::test(AdminSales::class)->set('search', (string) $order->id)->assertSee('Buyer');
    }

    public function test_admin_can_delete_only_questions_from_the_current_campaign(): void
    {
        $campaign = $this->campaign(User::factory()->create(), 'Question campaign');
        $question = Question::create(['campaign_id' => $campaign->id, 'question' => 'Remove this question']);
        $otherCampaign = $this->campaign(User::factory()->create(), 'Other campaign');
        $otherQuestion = Question::create(['campaign_id' => $otherCampaign->id, 'question' => 'Keep this question']);

        Livewire::test(AdminCampaignInfo::class, ['campaign' => $campaign])
            ->call('confirmDeleteQuestion', $question->id)
            ->call('deleteQuestion');

        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
        $this->assertDatabaseHas('questions', ['id' => $otherQuestion->id]);
    }

    public function test_approval_is_atomic_and_repeat_safe(): void
    {
        $wallet = $this->walletFor(User::factory()->create(), 10000, 5000, Wallet::STATUS_WITHDRAWAL_PENDING);
        $transaction = $wallet->transactions()->create([
            'current_balance' => 10000,
            'withdrawn_amount' => 5000,
            'credited_amount' => 0,
            'final_balance' => 5000,
            'status' => Transaction::STATUS_PENDING,
        ]);

        Livewire::test(AdminWallet::class)->call('approveWithdrawal', $transaction->id);
        Livewire::test(AdminWallet::class)->call('approveWithdrawal', $transaction->id);

        $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'status' => Transaction::STATUS_APPROVED, 'final_balance' => 5000]);
        $this->assertDatabaseHas('wallets', ['id' => $wallet->id, 'status' => Wallet::STATUS_ACTIVE, 'balance' => 5000]);
    }

    public function test_rejection_returns_reserved_funds_and_is_repeat_safe(): void
    {
        $wallet = $this->walletFor(User::factory()->create(), 10000, 5000, Wallet::STATUS_WITHDRAWAL_PENDING);
        $transaction = $wallet->transactions()->create([
            'current_balance' => 10000,
            'withdrawn_amount' => 5000,
            'credited_amount' => 0,
            'final_balance' => 5000,
            'status' => Transaction::STATUS_PENDING,
        ]);

        Livewire::test(AdminWallet::class)->call('rejectWithdrawal', $transaction->id);
        Livewire::test(AdminWallet::class)->call('rejectWithdrawal', $transaction->id);

        $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'status' => Transaction::STATUS_REJECTED, 'final_balance' => 10000]);
        $this->assertDatabaseHas('wallets', ['id' => $wallet->id, 'status' => Wallet::STATUS_ACTIVE, 'balance' => 10000]);
    }

    private function walletFor(User $user, int $earning, int $balance, int $status): Wallet
    {
        return $user->wallet()->create([
            'earning' => $earning,
            'balance' => $balance,
            'status' => $status,
        ]);
    }

    private function campaign(User $owner, string $title): Campaign
    {
        return Campaign::create([
            'user_id' => $owner->id,
            'title' => $title,
            'description' => 'A short campaign description.',
            'details' => 'Campaign details.',
            'currency' => 'RM',
            'price' => 5000,
            'fee' => 150,
            'start_date' => now()->subDay(),
            'end_date' => now()->addWeek(),
            'slug' => str($title)->slug().'-'.uniqid(),
            'variations' => [],
            'shipping' => [],
            'status' => 1,
        ]);
    }
}
