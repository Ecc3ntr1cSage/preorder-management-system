<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';
    protected $fillable = [
        'campaign_id',
        'question',
    ];

    protected $with = ['reply'];

    public function campaign(){
        return $this->belongsTo(Campaign::class);
    }
    public function reply(){
        return $this->hasOne(Reply::class);
    }

}
