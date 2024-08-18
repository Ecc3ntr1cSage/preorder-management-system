<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $table = 'campaigns';
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'details',
        'currency',
        'price',
        'fee',
        'start_date',
        'end_date',
        'slug',
        'variations',
        'shipping',
        'links',
        'status',
    ];

    protected $casts = [
        'variations' => 'array',
    ];
    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function coupon()
    {
        return $this->hasOne(Coupon::class);
    }
    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }
    // Route URL
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
