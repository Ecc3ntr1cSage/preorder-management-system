<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Coupon;
use App\Models\Image;
use App\Models\Order;
use App\Models\Question;
use App\Models\Reply;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Visitor;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        fake()->seed(20260803);

        $users = $this->seedUsers();
        $campaigns = $this->seedCampaigns($users);
        $this->seedQuestions($campaigns);
        $this->seedOrders($campaigns, $users);
        $this->seedVisitors($campaigns, $users);
    }

    private function seedUsers(): array
    {
        $password = Hash::make('password');

        $admin = User::create([
            'name' => 'Preshop Admin',
            'email' => 'admin@preshop.test',
            'password' => $password,
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $members = [
            'A' => ['name' => 'Maya Tan', 'email' => 'maya@preshop.test', 'phone' => '0123456789'],
            'B' => ['name' => 'Irfan Rahman', 'email' => 'irfan@preshop.test', 'phone' => '0134567890'],
            'C' => ['name' => 'Nadia Lim', 'email' => 'nadia@preshop.test', 'phone' => '0145678901'],
            'D' => ['name' => 'Daniel Wong', 'email' => 'daniel@preshop.test', 'phone' => '0167890123'],
            'E' => ['name' => 'Sofia Lee', 'email' => 'sofia@preshop.test', 'phone' => '0178901234'],
        ];

        foreach ($members as $key => $data) {
            $members[$key] = User::create($data + [
                'password' => $password,
                'is_admin' => false,
                'email_verified_at' => now(),
            ]);

            $members[$key]->wallet()->create([
                'earning' => 0,
                'balance' => 0,
                'status' => 1,
            ]);
        }

        return ['admin' => $admin] + $members;
    }

    private function seedCampaigns(array $users): array
    {
        $now = CarbonImmutable::now();
        $products = [
            'A' => ['title' => 'Sunday market tote', 'asset' => 'tote.png', 'description' => 'A sturdy canvas tote for the long way home.', 'price' => 4800, 'fee' => 150],
            'B' => ['title' => 'Late night radio tee', 'asset' => 'tee.png', 'description' => 'A soft cotton tee for people who keep the lights on.', 'price' => 6200, 'fee' => 190],
            'C' => ['title' => 'Small batch chili oil', 'asset' => 'chili-oil.png', 'description' => 'A bright, smoky pantry staple made in tiny batches.', 'price' => 2800, 'fee' => 90],
            'D' => ['title' => 'Rainy day field notes', 'asset' => 'field-notes.png', 'description' => 'A pocket notebook for plans, sketches, and half-finished thoughts.', 'price' => 2200, 'fee' => 70],
            'E' => ['title' => 'After work club cap', 'asset' => 'cap.png', 'description' => 'A low-profile cap for the hours after the day job.', 'price' => 3900, 'fee' => 120],
        ];

        $campaigns = [];

        foreach ($products as $key => $product) {
            $campaigns[$key]['completed'] = $this->createCampaign(
                $users[$key],
                $product,
                $now->subDays(70),
                $now->subDays(18),
                2
            );
        }

        foreach (['A', 'B', 'C'] as $key) {
            $product = $products[$key];
            $campaigns[$key]['ongoing'] = $this->createCampaign(
                $users[$key],
                array_merge($product, ['title' => $product['title'] . ' / second run']),
                $now->subDays(4),
                $now->addDays(24),
                1
            );
        }

        return $campaigns;
    }

    private function createCampaign(User $owner, array $product, CarbonImmutable $start, CarbonImmutable $end, int $status): Campaign
    {
        $campaign = Campaign::create([
            'user_id' => $owner->id,
            'title' => $product['title'],
            'description' => $product['description'],
            'details' => 'This community preorder collects early support before the next small production run begins. Every order helps the maker decide what to make next.',
            'currency' => 'RM',
            'price' => $product['price'],
            'fee' => $product['fee'],
            'start_date' => $start,
            'end_date' => $end,
            'slug' => str($product['title'])->slug(),
            'variations' => [['name' => 'Option', 'values' => 'Standard, Limited']],
            'shipping' => ['west_malaysia' => '6', 'sarawak' => '12', 'sabah' => '12'],
            'status' => $status,
        ]);

        $imageCount = fake()->randomElement([2, 3, 4]);
        for ($index = 1; $index <= $imageCount; $index++) {
            Image::create([
                'campaign_id' => $campaign->id,
                'image' => $this->copyProductImage($product['asset'], $campaign->id, $index),
            ]);
        }

        if ($status === 1) {
            Coupon::create([
                'campaign_id' => $campaign->id,
                'code' => 'BACK10',
                'discount' => 10,
                'limit' => 50,
                'usage' => 0,
                'expiry' => CarbonImmutable::now()->addDays(20),
            ]);
        }

        return $campaign;
    }

    private function seedQuestions(array $campaigns): void
    {
        foreach ($campaigns as $key => $ownerCampaigns) {
            $question = Question::create([
                'campaign_id' => $ownerCampaigns['completed']->id,
                'question' => $key === 'A'
                    ? 'Will the strap fit comfortably over a jacket?'
                    : 'When will the next small batch be ready?',
            ]);

            if (in_array($key, ['A', 'C', 'E'], true)) {
                Reply::create([
                    'question_id' => $question->id,
                    'reply' => 'Yes — the next batch is planned after this preorder closes.',
                ]);
            }
        }
    }

    private function seedOrders(array $campaigns, array $users): void
    {
        $buyersBySeller = [
            'A' => ['C', 'E'],
            'B' => ['A', 'D', 'E'],
            'C' => ['A', 'D', 'E'],
            'D' => ['A', 'B', 'C'],
            'E' => ['B', 'C', 'D'],
        ];

        foreach ($buyersBySeller as $sellerKey => $buyerKeys) {
            $campaign = $campaigns[$sellerKey]['completed'];

            foreach ($buyerKeys as $buyerKey) {
                $buyer = $users[$buyerKey];
                $quantity = fake()->numberBetween(1, 2);
                $shipping = fake()->randomElement([600, 800]);
                $subtotal = $campaign->price * $quantity;
                $amount = $subtotal + $shipping;
                $createdAt = CarbonImmutable::now()->subDays(fake()->numberBetween(2, 16));

                $order = Order::create([
                    'collection_id' => 'demo',
                    'campaign_id' => $campaign->id,
                    'user_id' => $buyer->id,
                    'email' => $buyer->email,
                    'name' => $buyer->name,
                    'phone' => $buyer->phone,
                    'status' => 3,
                    'amount' => $amount,
                    'discount' => 0,
                    'quantity' => $quantity,
                    'fee' => $campaign->fee * $quantity,
                    'shipping' => $shipping,
                    'variations' => 'Option: Standard',
                    'paid' => true,
                    'paid_at' => $createdAt,
                    'address' => '12 Jalan Damai',
                    'postcode' => '50450',
                    'state' => 'Selangor',
                    'tracking_number' => 'MYDEMO' . $campaign->id . $buyer->id,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $wallet = $users[$sellerKey]->wallet;
                $credit = $order->amount - $order->fee;
                $currentBalance = $wallet->fresh()->balance;
                $wallet->increment('earning', $credit);
                $wallet->increment('balance', $credit);

                Transaction::create([
                    'wallet_id' => $wallet->id,
                    'current_balance' => $currentBalance,
                    'withdrawn_amount' => 0,
                    'credited_amount' => $credit,
                    'final_balance' => $currentBalance + $credit,
                    'status' => 1,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }

    private function seedVisitors(array $campaigns, array $users): void
    {
        $memberKeys = array_keys(array_filter($users, fn ($user, $key) => $key !== 'admin', ARRAY_FILTER_USE_BOTH));
        $index = 0;

        foreach ($campaigns as $ownerCampaigns) {
            foreach ($ownerCampaigns as $campaign) {
                Visitor::create([
                    'campaign_id' => $campaign->id,
                    'user_id' => $users[$memberKeys[$index++ % count($memberKeys)]]->id,
                    'session_id' => 'demo-session-' . $campaign->id,
                ]);
            }
        }
    }

    private function copyProductImage(string $filename, int $campaignId, int $index): string
    {
        $source = public_path('asset/product/' . $filename);
        $target = 'campaign/' . $campaignId . '-' . $index . '.png';

        Storage::disk('public')->put($target, file_get_contents($source));

        return basename($target);
    }
}
