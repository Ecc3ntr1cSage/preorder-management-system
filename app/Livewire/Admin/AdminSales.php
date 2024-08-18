<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\WithPagination;
use Livewire\Component;

class AdminSales extends Component
{
    use WithPagination;

    public $perPage = 20;
    public $column = 'paid_at';
    public $direction = 'desc';
    public $search = '';

    public function resetFilter()
    {
        $this->perPage = 20;
        $this->column = 'paid_at';
        $this->direction = 'desc';
        $this->search = '';
    }

    public function sort($column,$direction)
    {
        $this->column = $column;
        $this->direction = $direction;
    }

    public function render()
    {
        $orders = Order::where(function ($query) {
            $query->where('email', 'like', '%' . $this->search . '%')
                ->orWhereHas('campaign', function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%');
                });
        })->orderBy($this->column, $this->direction)->paginate($this->perPage);

        return view('livewire.admin.admin-sales', compact('orders'));
    }
}
