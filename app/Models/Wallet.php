<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $table = 'wallets';
    protected $fillable = [
        'user_id',
        'bank_holder_name',
        'bank_name',
        'bank_account_number',
        'earning',
        'balance',
        'status'
    ];

    protected $with = ['transactions','withdrawal'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function withdrawal()
    {
        return $this->hasOne(Transaction::class)->where('status', 3);
    }
}
