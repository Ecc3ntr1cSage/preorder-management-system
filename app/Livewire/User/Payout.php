<?php

namespace App\Livewire\User;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Payout extends Component
{
    public Wallet $wallet;

    public $withdrawModal = false;

    #[Rule('required', message: 'I guess you don\'t want money.')]
    #[Rule('numeric', message: 'Incorrect format')]
    #[Rule('min:50', message: 'Minimum withdrawal of RM50.')]
    public $withdrawAmount;

    #[Rule('required', message: 'Please provide account holder name.')]
    public $name;

    #[Rule('required', message: 'Please provide bank name.')]
    public $bankName;

    #[Rule('required', message: 'Please provide account number.')]
    #[Rule('numeric', message: 'Incorrect format')]
    public $accountNumber;

    public function mount()
    {
        $this->wallet = Wallet::where('user_id', Auth::user()->id)->first();

        if ($this->checkBankDetails()) {
            $this->name = $this->wallet->bank_holder_name;
            $this->bankName = $this->wallet->bank_name;
            $this->accountNumber = $this->wallet->bank_account_number;
        }
    }

    private function checkBankDetails()
    {
        if ($this->wallet->bank_holder_name != null && $this->wallet->bank_name != null && $this->wallet->bank_account_number != null) {
            return true;
        } else {
            return false;
        }
    }

    public function validateWithdrawModal()
    {
        if (!$this->checkBankDetails()) {
            $this->dispatch('error', message: 'Incomplete Bank Details');
        } elseif ($this->wallet->balance == 0) {
            $this->dispatch('error', message: 'You have nothing to withdraw');
        } elseif ($this->wallet->status == 2){
            $this->dispatch('error', message: 'Withdrawal Request Pending');
        }
        else {
            $this->withdrawModal = true;
        }
    }

    public function updateBankDetails()
    {
        $this->validate([
            'name' => 'required',
            'bankName' => 'required',
            'accountNumber' => 'required|numeric'
        ]);

        $this->wallet->update([
            'bank_holder_name' => $this->name,
            'bank_name' => $this->bankName,
            'bank_account_number' => $this->accountNumber,
        ]);

        $this->dispatch('success', message: 'Bank Details Saved');
    }

    public function withdrawEarning()
    {
        $this->validate([
            'withdrawAmount' => 'required|numeric|min:50',
        ]);

        $amount = $this->withdrawAmount * 100;

        if ($amount > $this->wallet->balance) {
            $this->dispatch('error', message: 'Value exceeded available earnings');
        } else {
            Transaction::create([
                'wallet_id' => $this->wallet->id,
                'current_balance' => $this->wallet->balance,
                'withdrawn_amount' => $amount,
                'credited_amount' => 0,
                'final_balance' => $this->wallet->balance - $amount,
                'status' => 3,
            ]);

            $this->wallet->update([
                'balance' => $this->wallet->balance - $amount,
                'status' => 2,
            ]);
          
            $this->reset('withdrawAmount');
            $this->dispatch('success', message: 'Withdrawal Request Submitted');
        }
    }

    public function render()
    {
        return view('livewire.user.payout');
    }
}
