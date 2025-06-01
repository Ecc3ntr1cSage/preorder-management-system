<?php

namespace App\Livewire\Customer;

use Carbon\Carbon;
use App\Models\Campaign;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Payment extends Component
{
    #[Rule('required', message: 'Please provide an email.')]
    #[Rule('email', message: 'Incorrect email format.')]
    public $email;

    #[Rule('required', message: 'Please provide your name.')]
    public $name;

    #[Rule('required', message: 'Please provide a contact number.')]
    #[Rule('numeric', message: 'Phone has to be numeric.')]
    public $phone = '0183552589';

    #[Rule('required', message: 'Please provide an address.')]
    public $address = 'Shah Alam';

    #[Rule('required', message: 'Postal code can\'t be empty.')]
    #[Rule('required', message: 'Postal code invalid format.')]
    public $postcode;

    #[Rule('required', message: 'Please select a state.')]
    public $state;

    #[Rule('required', message: 'Please select payment options.')]
    public $bankCode;

    #[Rule('required', message: 'Can\'t be empty.')]
    public $couponCode;

    #[Locked]
    public $preorder, $campaign, $discount = 0, $shipping, $shippingArray = [];

    public function mount()
    {
        if (session('preorder') !== null) {
            $this->preorder = session('preorder');
            $this->email = Auth::user()->email;
            $this->name = Auth::user()->name;
        } else {
            return $this->redirect('/', navigate: true);
        }
    }

    public function updatedPostcode($value)
    {
        if (strlen($value) < 5) return;

        $this->state = $this->getState($value);
        $this->calculateShipping($this->state);
    }

    private function getState($postcode)
    {
        $postcode = str_pad($postcode, 5, '0', STR_PAD_LEFT);
        $ranges = config('countries.malaysia.postcodes');

        foreach ($ranges as $state => [$start, $end]) {
            if ($postcode >= $start && $postcode <= $end) {
                return ucwords(strtolower($state));
            }
        }

        return '';
    }

    public function applyCoupon()
    {
        $this->validate([
            'couponCode' => 'required'
        ]);

        $coupon = $this->campaign->coupon;

        if (strcasecmp($this->couponCode, $coupon->code) !== 0) {
            $this->dispatch('error', message: 'Invalid Coupon');
            return;
        }

        if ($coupon->limit <= $coupon->usage) {
            $this->dispatch('error', message: 'Limit Reached');
            return;
        }

        if (Carbon::now('GMT+8') > $coupon->expiry) {
            $this->dispatch('error', message: 'Coupon Expired');
            return;
        }

        $this->discount = $coupon->discount;
    }

    public function calculateShipping($state)
    {
        if (empty(array_filter($this->shippingArray))) return;

        $state = strtolower($state);

        if ($state === 'sarawak') {
            $this->shipping = $this->shippingArray['sarawak'];
        } elseif (in_array($state, ['sabah', 'labuan'])) {
            $this->shipping = $this->shippingArray['sabah'];
        } else {
            $this->shipping = $this->shippingArray['west_malaysia'];
        }
    }

    private function calculate()
    {
        $subtotal = $this->campaign->price * $this->preorder['quantity'];
        $total = $subtotal + $this->shipping * 100 - ($this->discount * 100);

        return [
            'subtotal' => $subtotal,
            'total' => $total,
        ];
    }

    public function payment()
    {
        $this->validate([
            'email' => 'required|email',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'postcode' => 'required',
            'state' => 'required',
            'bankCode' => 'required',
        ]);
        // Fetch shipping value
        $this->calculateShipping($this->state);
        // Initialize amount value
        $amount = round($this->calculate()['total']);
        // Post to billplz
        try {
            DB::beginTransaction();

            $order = Order::create([
                'collection_id' => env('BILLPLZ_COLLECTION'),
                'campaign_id'   => $this->preorder['campaign_id'],
                'user_id'       => auth()->id(),
                'email'         => $this->email,
                'name'          => $this->name,
                'phone'         => $this->phone,
                'status'        => 0,
                'amount'        => $amount,
                'discount'      => $this->discount * 100,
                'quantity'      => $this->preorder['quantity'],
                'fee'           => $this->campaign->fee * $this->preorder['quantity'],
                'shipping'      => $this->shipping * 100,
                'variations'    => $this->preorder['variations'],
                'paid'          => false,
                'address'       => $this->address,
                'postcode'      => $this->postcode,
                'state'         => $this->state,
            ]);

            $billplzPayload = [
                'collection_id'     => env('BILLPLZ_COLLECTION'),
                'email'             => $this->email,
                'name'              => $this->name,
                'mobile'            => $this->phone,
                'description'       => $this->campaign->title,
                'amount'            => $amount,
                'reference_1_label' => "Bank Code",
                'reference_1'       => $this->bankCode,
                'callback_url'      => route('billplz-callback'),
                'redirect_url'      => route('billplz-redirect', $order->uuid),
            ];

            $response = Http::withBasicAuth(env('BILLPLZ_KEY'), env('BILLPLZ_SIGNATURE'))
                ->post('https://www.billplz-sandbox.com/api/v3/bills', $billplzPayload);

            if (!$response->successful()) {
                Log::error('Billplz error: ' . $response->body());
                throw new \Exception('Payment gateway error');
            }

            $order->update(['billplz_id' => $response->json('id')]);

            DB::commit();

            return redirect("https://www.billplz-sandbox.com/bills/{$response->json('id')}?auto_submit=true");
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Payment failed: ' . $e->getMessage());
            abort(500, 'Something went wrong during payment.');
        }
    }

    #[Layout('layouts.guest')]
    public function render()
    {
        $this->campaign = Campaign::findOrFail($this->preorder['campaign_id']);
        $this->shippingArray = json_decode($this->campaign->shipping, true);
        $calculations = $this->calculate();

        return view('livewire.customer.payment', compact('calculations'));
    }
}
