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
use App\Models\Wallet;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = $this->seedUsers();
        $campaigns = $this->seedCampaigns($users);
        $this->seedQuestions($campaigns, $users);
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

        $users = collect([
            ['name' => 'Maya Tan', 'email' => 'maya@preshop.test', 'phone' => '0123456789'],
            ['name' => 'Irfan Rahman', 'email' => 'irfan@preshop.test', 'phone' => '0134567890'],
            ['name' => 'Nadia Lim', 'email' => 'nadia@preshop.test', 'phone' => '0145678901'],
            ['name' => 'Daniel Wong', 'email' => 'daniel@preshop.test', 'phone' => '0167890123'],
        ])->mapWithKeys(function (array $data) use ($password): array {
            $user = User::create($data + [
                'password' => $password,
                'is_admin' => false,
                'email_verified_at' => now(),
            ]);

            $user->wallet()->create([
                'earning' => 0,
                'balance' => 0,
                'status' => 1,
            ]);

            return [$user->name => $user];
        });

        return ['admin' => $admin] + $users->all();
    }

    private function seedCampaigns(array $users): array
    {
        $now = CarbonImmutable::now();
        $campaignData = [
            ['owner' => 'Maya Tan', 'title' => 'Sunday market tote', 'description' => 'A sturdy canvas tote for the long way home.', 'price' => 4800, 'fee' => 150, 'start' => -12, 'end' => 18, 'status' => 1, 'image' => 'checkout.webp'],
            ['owner' => 'Irfan Rahman', 'title' => 'Late night radio tee', 'description' => 'A soft cotton tee for people who keep the lights on.', 'price' => 6200, 'fee' => 190, 'start' => -26, 'end' => 7, 'status' => 1, 'image' => 'checkout2.webp'],
            ['owner' => 'Nadia Lim', 'title' => 'Small batch chili oil', 'description' => 'A bright, smoky pantry staple made in tiny batches.', 'price' => 2800, 'fee' => 90, 'start' => -40, 'end' => -4, 'status' => 2, 'image' => 'checkout.webp'],
            ['owner' => 'Daniel Wong', 'title' => 'Rainy day field notes', 'description' => 'A pocket notebook for plans, sketches, and half-finished thoughts.', 'price' => 2200, 'fee' => 70, 'start' => 2, 'end' => 30, 'status' => 1, 'image' => 'checkout2.webp'],
            ['owner' => 'Maya Tan', 'title' => 'After work club cap', 'description' => 'A low-profile cap for the hours after the day job.', 'price' => 3900, 'fee' => 120, 'start' => 5, 'end' => 35, 'status' => 1, 'image' => 'checkout.webp'],
        ];

        $campaigns = [];

        foreach ($campaignData as $data) {
            $campaign = Campaign::create([
                'user_id' => $users[$data['owner']]->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'details' => 'This demo campaign is collecting early support before production begins. Every order helps the maker decide what to make next.',
                'currency' => 'RM',
                'price' => $data['price'],
                'fee' => $data['fee'],
                'start_date' => $now->addDays($data['start']),
                'end_date' => $now->addDays($data['end']),
                'slug' => str($data['title'])->slug(),
                'variations' => [['name' => 'Option', 'values' => 'Standard, Limited']],
                'shipping' => ['west_malaysia' => '6', 'sarawak' => '12', 'sabah' => '12'],
                'status' => $data['status'],
            ]);

            $this->copyDemoImage($data['image'], $campaign->id);
            Image::create(['campaign_id' => $campaign->id, 'image' => $campaign->id . '-cover.webp']);
            $campaigns[$campaign->title] = $campaign;

            if ($campaign->status === 1) {
                Coupon::create([
                    'campaign_id' => $campaign->id,
                    'code' => 'BACK10',
                    'discount' => 10,
                    'limit' => 50,
                    'usage' => 6,
                    'expiry' => $now->addDays(20),
                ]);
            }
        }

        return $campaigns;
    }

    private function seedQuestions(array $campaigns, array $users): void
    {
        $question = Question::create([
            'campaign_id' => $campaigns['Sunday market tote']->id,
            'question' => 'Will the strap fit comfortably over a jacket?',
        ]);

        Reply::create([
            'question_id' => $question->id,
            'reply' => 'Yes — the strap is sized for a shoulder carry over light layers.',
        ]);

        Question::create([
            'campaign_id' => $campaigns['Late night radio tee']->id,
            'question' => 'Will there be more sizes after this preorder?',
        ]);
    }

    private function seedOrders(array $campaigns, array $users): void
    {
        $orderData = [
            ['campaign' => 'Sunday market tote', 'buyer' => 'Irfan Rahman', 'status' => 3, 'paid' => true, 'quantity' => 2, 'days' => -8],
            ['campaign' => 'Sunday market tote', 'buyer' => 'Nadia Lim', 'status' => 2, 'paid' => true, 'quantity' => 1, 'days' => -5],
            ['campaign' => 'Late night radio tee', 'buyer' => 'Maya Tan', 'status' => 1, 'paid' => true, 'quantity' => 1, 'days' => -3],
            ['campaign' => 'Rainy day field notes', 'buyer' => 'Maya Tan', 'status' => 1, 'paid' => true, 'quantity' => 3, 'days' => -1],
            ['campaign' => 'After work club cap', 'buyer' => 'Daniel Wong', 'status' => 0, 'paid' => false, 'quantity' => 1, 'days' => 0],
        ];

        foreach ($orderData as $data) {
            $campaign = $campaigns[$data['campaign']];
            $buyer = $users[$data['buyer']];
            $createdAt = now()->addDays($data['days']);
            $subtotal = $campaign->price * $data['quantity'];
            $shipping = 600;
            $amount = $subtotal + $shipping;

            Order::create([
                'billplz_id' => null,
                'collection_id' => 'demo',
                'campaign_id' => $campaign->id,
                'user_id' => $buyer->id,
                'email' => $buyer->email,
                'name' => $buyer->name,
                'phone' => $buyer->phone,
                'status' => $data['status'],
                'amount' => $amount,
                'discount' => 0,
                'quantity' => $data['quantity'],
                'fee' => $campaign->fee * $data['quantity'],
                'shipping' => $shipping,
                'variations' => 'Option: Standard',
                'paid' => $data['paid'],
                'paid_at' => $data['paid'] ? $createdAt : null,
                'address' => '12 Jalan Damai',
                'postcode' => '50450',
                'state' => 'Selangor',
                'tracking_number' => $data['status'] >= 2 ? 'MYDEMO' . $campaign->id . $buyer->id : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            if ($data['paid']) {
                $wallet = $campaign->user->wallet;
                $credit = $amount - ($campaign->fee * $data['quantity']);
                $wallet->increment('earning', $credit);
                $wallet->increment('balance', $credit);
                Transaction::create([
                    'wallet_id' => $wallet->id,
                    'current_balance' => $wallet->balance - $credit,
                    'withdrawn_amount' => 0,
                    'credited_amount' => $credit,
                    'final_balance' => $wallet->balance,
                    'status' => 1,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }

    private function seedVisitors(array $campaigns, array $users): void
    {
        foreach (array_values($campaigns) as $index => $campaign) {
            Visitor::create([
                'campaign_id' => $campaign->id,
                'user_id' => array_values($users)[($index % 4) + 1]->id,
                'session_id' => 'demo-session-' . $campaign->id,
            ]);
        }
    }

    private function copyDemoImage(string $filename, int $campaignId): void
    {
        $source = public_path('asset/' . $filename);
        Storage::disk('public')->put('campaign/' . $campaignId . '-cover.webp', file_get_contents($source));
    }
}
