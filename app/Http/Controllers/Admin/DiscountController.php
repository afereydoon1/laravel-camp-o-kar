<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountRequest;
use App\Http\Resources\DiscountCollection;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return DiscountCollection
     */
    public function index()
    {
        return new DiscountCollection(Discount::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DiscountRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(DiscountRequest $request)
    {
        Discount::create([
            'percent' => $request->percent,
            'code' => Str::random(10),
        ]);

        return response(['ok'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Discount  $discount
     * @return Discount
     */
    public function show(Discount $discount)
    {
        return $discount;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(DiscountRequest $request, Discount $discount)
    {
        $discount->update($request->only('percent'));

        return response(['ok'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        $discount->delete();

        return response(['ok'], 200);
    }
}
