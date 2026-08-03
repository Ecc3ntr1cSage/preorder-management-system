<?php

namespace Tests\Feature;

use App\Livewire\Customer\Payment;
use App\Models\Campaign;
use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use Tests\TestCase;

class MarketplaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_discovery_and_campaign_detail_render(): void
    {
        $campaign = $this->campaign(User::factory()->create());
        Image::create(['campaign_id' => $campaign->id, 'image' => 'demo.webp']);

        $this->get('/shop')->assertOk();
        $this->get('/' . $campaign->slug)->assertOk();
    }

    public function test_admin_routes_require_admin_access(): void
    {
        $this->get('/admin/users')->assertRedirect('/login');
        $this->actingAs(User::factory()->create())->get('/admin/users')->assertForbidden();
        $this->actingAs(User::factory()->create(['is_admin' => true]))->get('/admin/users')->assertOk();
    }

    public function test_campaign_management_is_owner_only(): void
    {
        $owner = User::factory()->create();
        $campaign = $this->campaign($owner);

        $this->actingAs(User::factory()->create())
            ->get('/campaigns/' . $campaign->slug)
            ->assertForbidden();
    }

    public function test_local_checkout_credits_owner_wallet_once(): void
    {
        $owner = User::factory()->create();
        $buyer = User::factory()->create();
        $owner->wallet()->create(['earning' => 0, 'balance' => 0, 'status' => 1]);
        $campaign = $this->campaign($owner);

        $this->actingAs($buyer);
        session(['preorder' => [
            'campaign_id' => $campaign->id,
            'quantity' => 2,
            'variations' => 'Standard',
        ]]);

        Livewire::test(Payment::class)
            ->set('postcode', '40150')
            ->set('state', 'Selangor')
            ->call('payment')
            ->call('payment');

        $this->assertDatabaseCount('orders', 1);
        $this->assertSame(2, $campaign->orders()->first()->quantity);
        $this->assertSame($campaign->price * 2 - $campaign->fee * 2, $owner->wallet()->first()->fresh()->balance);
    }

    public function test_database_seeder_provides_repeatable_demo_accounts_and_records(): void
    {
        Artisan::call('db:seed');

        $this->assertDatabaseHas('users', ['email' => 'admin@preshop.test', 'is_admin' => 1]);
        $this->assertDatabaseCount('users', 6);
        $this->assertDatabaseCount('campaigns', 8);
        $this->assertGreaterThanOrEqual(16, \App\Models\Image::count());
        $this->assertLessThanOrEqual(32, \App\Models\Image::count());
        $this->assertDatabaseCount('orders', 14);
        $this->assertDatabaseCount('visitors', 8);
        $this->assertDatabaseCount('wallets', 5);
        $this->assertSame(3, Campaign::where('status', 1)->count());
        $this->assertSame(5, Campaign::where('status', 2)->count());

        foreach (['maya@preshop.test', 'irfan@preshop.test', 'nadia@preshop.test', 'daniel@preshop.test', 'sofia@preshop.test'] as $email) {
            $this->assertSame(1, Campaign::where('user_id', User::where('email', $email)->value('id'))->where('status', 2)->count());
        }

        foreach (['maya@preshop.test' => 3, 'irfan@preshop.test' => 2, 'nadia@preshop.test' => 3, 'daniel@preshop.test' => 3, 'sofia@preshop.test' => 3] as $email => $count) {
            $this->assertSame($count, User::where('email', $email)->first()->orders()->count());
        }
    }

    private function campaign(User $owner): Campaign
    {
        return Campaign::create([
            'user_id' => $owner->id,
            'title' => 'Demo campaign ' . uniqid(),
            'description' => 'A short campaign description.',
            'details' => 'Campaign details.',
            'currency' => 'RM',
            'price' => 5000,
            'fee' => 150,
            'start_date' => now()->subDay(),
            'end_date' => now()->addWeek(),
            'slug' => 'demo-' . uniqid(),
            'variations' => [],
            'shipping' => [],
            'status' => 1,
        ]);
    }
}
