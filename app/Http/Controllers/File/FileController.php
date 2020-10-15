<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserFileCollection;
use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except('index');
    }

    public function index()
    {
        return new UserFileCollection(
            File::with('categories', 'membership')
                ->sortByUrl()
                ->searchByUrl()
                ->paginate(10)
        );
    }

    public function show(File $file)
    {
        return $file->load('categories', 'confirmed_comments.user')
            ->append([
                'price_in_toman', 'membership_name', 'related_files', 'is_user_liked'
            ]);
    }
}
