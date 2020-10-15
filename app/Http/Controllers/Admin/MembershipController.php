<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MembershipRequest;
use App\Http\Resources\MembershipCollection;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return MembershipCollection
     */
    public function index()
    {
        return new MembershipCollection(Membership::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MembershipRequest $request)
    {
        Membership::create($request->validated());

        return response(['ok'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Membership  $membership
     * @return Membership
     */
    public function show(Membership $membership)
    {
        return $membership;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function update(MembershipRequest $request, Membership $membership)
    {
        $membership->update($request->validated());

        return response(['ok'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Membership $membership
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Membership $membership)
    {
        $membership->delete();

        return response(['ok'], 200);
    }
}
