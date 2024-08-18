<?php

namespace App\Livewire\User;

use Carbon\Carbon;
use App\Models\Campaign;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Payment extends Component
{
    #[Rule('required', message: 'Please provide an email.')]
    #[Rule('email', message: 'Incorrect email format.')]
    public $email = 'imeor0655@gmail.com';

    #[Rule('required', message: 'Please provide your name.')]
    public $name = 'Meor Izzuddin';

    #[Rule('required', message: 'Please provide a contact number.')]
    #[Rule('numeric', message: 'Phone has to be numeric.')]
    public $phone = '0183552589';

    #[Rule('required', message: 'Please provide an address.')]
    public $address = 'A-25-23A, Canopy Hills';

    #[Rule('required', message: 'Postal code can\'t be empty.')]
    #[Rule('required', message: 'Postal code invalid format.')]
    public $postcode = '43500';

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
        } else {
            return $this->redirect('/', navigate: true);
        }
    }

    public function applyCoupon()
    {
        $this->validate([
            'couponCode' => 'required'
        ]);

        $coupon = $this->campaign->coupon;

        if ($this->couponCode !== $coupon->code) {
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
        if (empty(array_filter($this->shippingArray))) {
            return;
        }
        if ($state == 'Sarawak') {
            $this->shipping = $this->shippingArray['sarawak'];
        } elseif ($state == 'Sabah' || $state == 'Labuan') {
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
        $billplz = array(
            'collection_id' => env('BILLPLZ_COLLECTION'),
            'email' => $this->email,
            'name' => $this->name,
            'mobile' => $this->phone,
            'description' => $this->campaign->title,
            'amount' => $amount,
            'reference_1_label' => "Bank Code",
            'reference_1' => $this->bankCode,
            'callback_url' => route('billplz-callback'),
            'redirect_url' => route('billplz-redirect'),
        );
        // Initialize payment data
        $paymentData = [
            'campaign_id' => $this->preorder['campaign_id'],
            'quantity' => $this->preorder['quantity'],
            'variations' => $this->preorder['variations'],
            'email' => $this->email,
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'postcode' => $this->postcode,
            'state' => $this->state,
            'amount' => $billplz['amount'],
            'fee' => $this->campaign->fee * $this->preorder['quantity'],
            'shipping' => $this->shipping * 100,
        ];
        // If coupon is applied
        if ($this->discount !== 0) {
            $paymentData['coupon_id'] = $this->campaign->coupon->id;
        }
        // Store payment data to session
        session(['payment' => $paymentData]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.billplz-sandbox.com/api/v3/bills');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $billplz);
        curl_setopt($ch, CURLOPT_USERPWD, env('BILLPLZ_KEY') . ':' . env('BILLPLZ_SIGNATURE'));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        $obj = json_decode($result);
        $billId = $obj->id;

        return redirect('https://www.billplz-sandbox.com/bills/' . $billId . '?auto_submit=true');
    }

    #[Layout('layouts.guest')]
    public function render()
    {
        $this->campaign = Campaign::findOrFail($this->preorder['campaign_id']);
        $this->shippingArray = json_decode($this->campaign->shipping, true);
        $calculations = $this->calculate();

        return view('livewire.user.payment', compact('calculations'));
    }
}
