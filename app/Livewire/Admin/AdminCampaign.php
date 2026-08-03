<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use Livewire\WithPagination;
use Livewire\Component;

class AdminCampaign extends Component
{
    use WithPagination;

    public int $perPage = 12;
    public string $column = 'created_at';
    public string $direction = 'desc';
    public string $search = '';
    public string $statusFilter = '';

    private const SORTABLE_COLUMNS = ['created_at', 'title', 'price', 'end_date'];

    public function resetFilter()
    {
        $this->perPage = 12;
        $this->column = 'created_at';
        $this->direction = 'desc';
        $this->search = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function updatedSearch(): void
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
        $column = in_array($this->column, self::SORTABLE_COLUMNS, true) ? $this->column : 'created_at';
        $direction = in_array($this->direction, ['asc', 'desc'], true) ? $this->direction : 'desc';
        $perPage = min(max($this->perPage, 1), 48);

        $campaigns = Campaign::query()
            ->with('user')
            ->withCount('orders')
            ->when($this->statusFilter !== '', fn ($query) => $query->where('status', (int) $this->statusFilter))
            ->where(function ($query) {
            $query->where('title', 'like', '%' . $this->search . '%')
                ->orWhereHas('user', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($column, $direction)
            ->paginate($perPage);

        return view('livewire.admin.admin-campaign', compact('campaigns'));
    }
}
