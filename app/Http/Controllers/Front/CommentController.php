<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        $comment = Comment::create([
            'file_id' => $request->file_id,
            'user_id' => $request->user()->id,
            'comment_id' => $request->comment_id,
            'body' => $request->body,
        ]);

        return response(['data' => $comment->load('user')],200);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response(['ok'], 200);
    }
}
