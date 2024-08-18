<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $table = 'visitors';
    protected $fillable = [
        'campaign_id',
        'session_id',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
