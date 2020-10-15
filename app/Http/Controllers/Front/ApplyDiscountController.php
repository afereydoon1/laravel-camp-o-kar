<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyDiscountRequest;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ApplyDiscountController extends Controller
{
    public function store(ApplyDiscountRequest $request)
    {
        $discount = Discount::whereCode($request->discount)->first();

        $price = $discount->getRealPrice($request->price . '000');

        if ($price < 1000) {
            throw ValidationException::withMessages([
                'discount' => 'قیمت فایل نباید از هزار تومن کمتر باشد!'
            ]);
        }

        return ['id' => $discount->id, 'price' => $price];
    }
}
