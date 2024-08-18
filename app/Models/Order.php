<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'billplz_id',
        'collection_id',
        'campaign_id',
        'email',
        'name',
        'phone',
        'status',
        'amount',
        'discount',
        'quantity',
        'fee',
        'shipping',
        'variations',
        'paid',
        'paid_at',
        'address',
        'postcode',
        'state',
        'tracking_number'
    ];

    public function getRouteKeyName()
    {
        return 'billplz_id';
    }

    public function campaign(){
        return $this->belongsTo(Campaign::class);
    }

    public function user(){
        return $this->campaign->belongsTo(User::class);
    }
}
