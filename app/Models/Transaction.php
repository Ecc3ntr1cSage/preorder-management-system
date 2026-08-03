<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public const STATUS_CREDITED = 1;
    public const STATUS_APPROVED = 2;
    public const STATUS_PENDING = 3;
    public const STATUS_REJECTED = 4;

    protected $table = 'transactions';
    protected $fillable = [
        'wallet_id',
        'current_balance',
        'withdrawn_amount',
        'credited_amount',
        'final_balance',
        'status',
    ];

    public function wallet(){
        return $this->belongsTo(Wallet::class);
    }

    public function user(){
        return $this->belongsTo(User::class)->through('wallet');
    }
}
