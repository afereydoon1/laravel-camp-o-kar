<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        return Comment::with('user','file')
            ->whereIsConfirmed(false)
            ->paginate(10);
    }

    public function update(Request $request, Comment $comment)
    {
        $comment->update(['is_confirmed' => $request->is_confirmed]);

        return response(['ok'], 200);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response(['ok'], 200);
    }
}
