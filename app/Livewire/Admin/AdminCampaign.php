<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithPagination;
use Livewire\Component;

class AdminCampaign extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $column = 'created_at';
    public $direction = 'desc';
    public $search = '';

    public function resetFilter()
    {
        $this->perPage = 10;
        $this->column = 'created_at';
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
        $campaigns = Campaign::where(function ($query) {
            $query->where('title', 'like', '%' . $this->search . '%')
                ->orWhereHas('user', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                });
        })->orderBy($this->column, $this->direction)->paginate($this->perPage);

        return view('livewire.admin.admin-campaign', compact('campaigns'));
    }
}
