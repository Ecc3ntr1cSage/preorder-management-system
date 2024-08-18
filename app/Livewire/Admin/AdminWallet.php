<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use App\Models\Wallet;
use Livewire\WithPagination;
use Livewire\Component;

class AdminWallet extends Component
{
    use WithPagination;

    public $approveModal = false;
    public $walletPerPage = 10;
    public $transactionPerPage = 10;
    public $searchWallet = '';
    public $searchTransaction = '';


    public function resetTransaction()
    {
        $this->transactionPerPage = 10;
        $this->searchTransaction = '';
    }

    public function resetWallet()
    {
        $this->walletPerPage = 10;
        $this->searchWallet = '';
    }

    public function approveWithdraw($wallet_id)
    {
        $wallet = Wallet::findOrFail($wallet_id);
        $wallet->update([
            'status' => 1,
        ]);
        $wallet->withdrawal->update([
            'status' => 2,
        ]);

        $this->approveModal = false;
        $this->dispatch('success', message: 'Withdrawal Request Approved');
    }

    public function render()
    {
        $wallets = Wallet::whereHas('user', function($query){
            $query->where('name', 'like', '%' . $this->searchWallet . '%');
        })->orderBy('created_at', 'desc')->paginate($this->walletPerPage);
        
        $transactions = Transaction::whereHas('wallet.user', function ($query) {
            $query->where('name', 'like', '%' . $this->searchTransaction . '%');
        })->orderBy('created_at', 'desc')->paginate($this->transactionPerPage);

        return view('livewire.admin.admin-wallet', compact('wallets', 'transactions'));
    }
}
