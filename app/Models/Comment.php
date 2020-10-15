<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id', 'file_id', 'comment_id', 'body', 'is_confirmed'
    ];

    protected $with = ['confirmed_comments.user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function confirmed_comments()
    {
        return $this->hasMany(Comment::class)
            ->whereIsConfirmed(true);
    }
}
