<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'name', 'price', 'description', 'is_yearly', 'priority'
    ];

    protected $casts = [
        'is_yearly' => 'boolean'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
                ->withPivot(['expire_at', 'payment_id'])
                ->withTimestamps();
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }


    protected $appends = ['type', 'price_in_toman'];

    public function getTypeAttribute()
    {
        return $this->is_yearly ? 'سالیانه' : 'ماهیانه';
    }

    public function getPriceInTomanAttribute()
    {
        return $this->price . '000 هزار تومن';
    }

    public function getTomanPriceAttribute()
    {
        return (int) ($this->price . '000');
    }


    public function getExpireAtAttribute()
    {
        return $this->is_yearly ? now()->addYear() : now()->addMonth();
    }
}
