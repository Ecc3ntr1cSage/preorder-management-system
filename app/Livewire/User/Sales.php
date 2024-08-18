<?php

namespace App\Livewire\User;

use App\Models\Campaign;
use App\Models\Order;
use App\Exports\ExportSales;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Component;
use Livewire\WithPagination;

class Sales extends Component
{
    use WithPagination;

    public $sortBy = null;
    public $perPage = 10;
    public $column = 'paid_at';
    public $direction = 'desc';
    public $search = '';
    
    public function clearFilter()
    {
        $this->sortBy = null;
        $this->perPage = 10;
        $this->column = 'paid_at';
        $this->direction = 'desc';
    }

    public function sortOrder($campaign_id)
    {
        $this->sortBy = $campaign_id;
    }

    public function sort_direction($column,$direction)
    {
        $this->column = $column;
        $this->direction = $direction;
    }

    public function export($campaign_id){
        $orders = Order::select('email','name','phone','quantity','amount','shipping','variations','address','postcode','state','paid_at')
            ->where('campaign_id', $campaign_id)
            ->get();
        $campaign = Campaign::findOrFail($campaign_id);
        $filename = str_replace(' ', '_', $campaign->title) . '.xlsx';

        return Excel::download(new ExportSales($orders), $filename);
    }

    public function render()
    {
        $orders = Order::whereHas('campaign', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->when($this->sortBy, function ($query) {
            $query->where('campaign_id', $this->sortBy);
        })->where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
        })->orderBy($this->column, $this->direction)->paginate($this->perPage);

        $campaigns = Campaign::where('user_id', Auth::user()->id)->select('id','title')->get();
        
        return view('livewire.user.sales', compact('orders','campaigns'));
    }
}
