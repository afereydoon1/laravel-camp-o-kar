<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\MembershipResouceCollection;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index()
    {
        return MembershipResouceCollection::make(
            Membership::orderBy('priority')->get()->groupBy('is_yearly')
        );
    }
}
