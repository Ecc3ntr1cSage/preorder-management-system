<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Livewire\Component;

class AdminWallet extends Component
{
    use WithPagination;

    public bool $confirmModal = false;
    public int $walletPerPage = 10;
    public int $transactionPerPage = 15;
    public int $withdrawalPerPage = 10;
    public string $searchWallet = '';
    public string $searchTransaction = '';
    public string $searchWithdrawal = '';
    public ?int $selectedTransactionId = null;
    public string $selectedAction = '';


    public function resetTransaction()
    {
        $this->transactionPerPage = 10;
        $this->searchTransaction = '';
        $this->resetPage('transactionPage');
    }

    public function resetWallet()
    {
        $this->walletPerPage = 10;
        $this->searchWallet = '';
        $this->resetPage('walletPage');
    }

    public function updatedSearchWithdrawal(): void
    {
        $this->resetPage('withdrawalPage');
    }

    public function updatedWalletPerPage(): void
    {
        $this->resetPage('walletPage');
    }

    public function updatedTransactionPerPage(): void
    {
        $this->resetPage('transactionPage');
    }

    public function updatedWithdrawalPerPage(): void
    {
        $this->resetPage('withdrawalPage');
    }

    public function updatedSearchWallet(): void
    {
        $this->resetPage('walletPage');
    }

    public function updatedSearchTransaction(): void
    {
        $this->resetPage('transactionPage');
    }

    public function approveWithdrawal(int $transactionId): void
    {
        $this->transitionWithdrawal($transactionId, Transaction::STATUS_APPROVED);
    }

    public function rejectWithdrawal(int $transactionId): void
    {
        $this->transitionWithdrawal($transactionId, Transaction::STATUS_REJECTED);
    }

    public function confirmWithdrawal(int $transactionId, string $action): void
    {
        if (! in_array($action, ['approve', 'reject'], true)) {
            return;
        }

        $this->selectedTransactionId = $transactionId;
        $this->selectedAction = $action;
        $this->confirmModal = true;
    }

    public function executeWithdrawal(): void
    {
        if (! $this->selectedTransactionId || ! $this->selectedAction) {
            return;
        }

        $status = $this->selectedAction === 'approve'
            ? Transaction::STATUS_APPROVED
            : Transaction::STATUS_REJECTED;

        $this->transitionWithdrawal($this->selectedTransactionId, $status);
        $this->confirmModal = false;
        $this->selectedTransactionId = null;
        $this->selectedAction = '';
    }

    private function transitionWithdrawal(int $transactionId, int $status): void
    {
        $handled = DB::transaction(function () use ($transactionId, $status): bool {
            $transaction = Transaction::query()->lockForUpdate()->findOrFail($transactionId);
            $wallet = Wallet::query()->lockForUpdate()->findOrFail($transaction->wallet_id);

            if ($transaction->status !== Transaction::STATUS_PENDING || $wallet->status !== Wallet::STATUS_WITHDRAWAL_PENDING) {
                return false;
            }

            if ($status === Transaction::STATUS_REJECTED) {
                $wallet->increment('balance', $transaction->withdrawn_amount);
                $wallet->refresh();
                $transaction->final_balance = $wallet->balance;
            }

            $wallet->update(['status' => Wallet::STATUS_ACTIVE]);
            $transaction->update([
                'status' => $status,
                'final_balance' => $transaction->final_balance,
            ]);

            return true;
        });

        $this->dispatch($handled ? 'success' : 'error', message: $handled
            ? ($status === Transaction::STATUS_APPROVED ? 'Withdrawal approved' : 'Withdrawal rejected and funds returned')
            : 'This withdrawal is no longer pending');
    }

    public function render()
    {
        $walletPerPage = min(max($this->walletPerPage, 1), 100);
        $transactionPerPage = min(max($this->transactionPerPage, 1), 100);
        $withdrawalPerPage = min(max($this->withdrawalPerPage, 1), 100);

        $wallets = Wallet::query()
            ->with('user')
            ->whereHas('user', fn ($query) => $query->where('name', 'like', '%' . $this->searchWallet . '%'))
            ->latest()
            ->paginate($walletPerPage, ['*'], 'walletPage');

        $transactions = Transaction::query()
            ->with('wallet.user')
            ->whereHas('wallet.user', fn ($query) => $query->where('name', 'like', '%' . $this->searchTransaction . '%'))
            ->latest()
            ->paginate($transactionPerPage, ['*'], 'transactionPage');

        $withdrawals = Transaction::query()
            ->with('wallet.user')
            ->where('status', Transaction::STATUS_PENDING)
            ->whereHas('wallet.user', fn ($query) => $query->where('name', 'like', '%' . $this->searchWithdrawal . '%'))
            ->latest()
            ->paginate($withdrawalPerPage, ['*'], 'withdrawalPage');

        return view('livewire.admin.admin-wallet', compact('wallets', 'transactions', 'withdrawals'));
    }
}
