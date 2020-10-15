<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeFileController extends Controller
{
    public function like(Request $request, File $file)
    {
        $like = Like::firstOrCreate([
            'file_id' => $file->id,
            'user_id' => $request->user()->id
        ]);

        $like->update(['is_liked' => ! $like->is_liked]);

        return response(['ok'], 200);
    }
}
