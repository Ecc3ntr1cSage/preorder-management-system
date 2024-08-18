<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';
    protected $fillable = [
        'campaign_id',
        'code',
        'discount',
        'limit',
        'expiry',
        'usage',
    ];

    public function campaign(){
        return $this->belongsTo(Campaign::class);
    }
}
