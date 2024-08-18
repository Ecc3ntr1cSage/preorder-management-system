<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $table = 'replies';
    protected $fillable = [
        'question_id',
        'reply',
    ];

    public function question(){
        return $this->belongsTo(Question::class);
    }
}
