<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\WithPagination;
use Livewire\Component;

class AdminSales extends Component
{
    use WithPagination;

    public int $perPage = 20;
    public string $column = 'paid_at';
    public string $direction = 'desc';
    public string $search = '';
    public string $paymentFilter = '';
    public string $statusFilter = '';

    private const SORTABLE_COLUMNS = ['paid_at', 'created_at', 'amount', 'status'];

    public function resetFilter()
    {
        $this->perPage = 20;
        $this->column = 'paid_at';
        $this->direction = 'desc';
        $this->search = '';
        $this->paymentFilter = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPaymentFilter(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function sort(string $column, string $direction): void
    {
        if (! in_array($column, self::SORTABLE_COLUMNS, true) || ! in_array($direction, ['asc', 'desc'], true)) {
            return;
        }

        $this->column = $column;
        $this->direction = $direction;
        $this->resetPage();
    }

    public function render()
    {
        $column = in_array($this->column, self::SORTABLE_COLUMNS, true) ? $this->column : 'paid_at';
        $direction = in_array($this->direction, ['asc', 'desc'], true) ? $this->direction : 'desc';
        $perPage = min(max($this->perPage, 1), 80);

        $orders = Order::query()
            ->with('campaign')
            ->when($this->paymentFilter !== '', fn ($query) => $query->where('paid', $this->paymentFilter === 'paid'))
            ->when($this->statusFilter !== '', fn ($query) => $query->where('status', (int) $this->statusFilter))
            ->where(function ($query) {
            $query->where('id', 'like', '%' . $this->search . '%')
                ->orWhere('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhereHas('campaign', function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($column, $direction)
            ->paginate($perPage);

        return view('livewire.admin.admin-sales', compact('orders'));
    }
}
