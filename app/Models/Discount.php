<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = ['percent', 'code'];

    public function getRouteKeyName()
    {
        return 'code';
    }

    public function getRealPrice($price)
    {
        return $price * ((100 - $this->percent) / 100);
    }

    public function getDiscountedPrice($price)
    {
        return $price * ($this->percent / 100);
    }
}
