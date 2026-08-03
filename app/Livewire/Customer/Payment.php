<?php

namespace App\Livewire\Customer;

use Carbon\Carbon;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Rule;
use Livewire\Component;

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

        if (!$coupon || strcasecmp($this->couponCode, $coupon->code) !== 0) {
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
        $shipping = (float) ($this->shipping ?: 0) * 100;
        $discount = round($subtotal * ((float) $this->discount / 100));
        $total = $subtotal + $shipping - $discount;

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
        ];
    }

    public function payment()
    {
        if (!session()->has('preorder')) {
            return $this->redirectRoute('customer.shop', navigate: true);
        }

        $this->validate([
            'email' => 'required|email',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'postcode' => 'required',
            'state' => 'required',
        ]);

        $this->calculateShipping($this->state);
        $calculations = $this->calculate();

        $order = DB::transaction(function () use ($calculations) {
            $order = $this->campaign->orders()->create([
                'campaign_id'   => $this->preorder['campaign_id'],
                'collection_id' => 'demo',
                'user_id'       => auth()->id(),
                'email'         => $this->email,
                'name'          => $this->name,
                'phone'         => $this->phone,
                'status'        => 1,
                'amount'        => $calculations['total'],
                'discount'      => $calculations['discount'],
                'quantity'      => $this->preorder['quantity'],
                'fee'           => $this->campaign->fee * $this->preorder['quantity'],
                'shipping'      => (float) ($this->shipping ?: 0) * 100,
                'variations'    => $this->preorder['variations'],
                'paid'          => true,
                'paid_at'       => now(),
                'address'       => $this->address,
                'postcode'      => $this->postcode,
                'state'         => $this->state,
            ]);

            if ($this->discount > 0) {
                $this->campaign->coupon?->increment('usage');
            }

            $wallet = $this->campaign->user->wallet()->firstOrCreate([], ['earning' => 0, 'balance' => 0]);
            $credit = $order->amount - $order->fee;
            $wallet->increment('earning', $credit);
            $wallet->increment('balance', $credit);

            return $order;
        });

        session()->forget('preorder');

        return $this->redirectRoute('customer.invoice', $order, navigate: true);
    }

    #[Layout('layouts.guest')]
    public function render()
    {
        $this->campaign = Campaign::findOrFail($this->preorder['campaign_id']);
        $this->shippingArray = is_array($this->campaign->shipping)
            ? $this->campaign->shipping
            : (json_decode($this->campaign->shipping, true) ?: []);
        $calculations = $this->calculate();

        return view('livewire.customer.payment', compact('calculations'));
    }
}
