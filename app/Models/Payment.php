<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'price', 'original_price', 'discounted_price',
        'user_id', 'ref_id', 'authority',
        'is_payed', 'extra_details',
        'paymentable_id', 'paymentable_type'
    ];

    protected $casts = [
        'is_payed' => 'boolean',
        'extra_details' => 'json'
    ];

    protected $appends = [
        'price_in_toman', 'original_price_in_toman', 'discounted_in_toman',
        'type_class', 'payed_type'
    ];

    public function paymentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getPriceInTomanAttribute()
    {
        return $this->price . ' تومان';
    }

    public function getOriginalPriceInTomanAttribute()
    {
        return $this->original_price . ' تومان';
    }

    public function getDiscountedInTomanAttribute()
    {
        return $this->discounted_price
            ? $this->discounted_price . ' تومان'
            : 'تخفیف ندارد';
    }

    public function getTypeClassAttribute()
    {
        return $this->paymentable_type === 'App\Models\File' ? 'فایل' : 'اشتراک ویژه';
    }
    public function getTypeAttribute()
    {
        return $this->paymentable_type === 'App\Models\File' ? 'file' : 'membership';
    }

    public function getPayedTypeAttribute()
    {
        return $this->is_payed ? 'پرداخت شده' : 'به مشکل خورد';
    }

    public function getCreatedTimeAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }

}
