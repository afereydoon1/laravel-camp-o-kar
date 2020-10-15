<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'file_id', 'user_id', 'is_liked'
    ];

    protected $casts = [
        'is_liked' => 'boolean'
    ];

}
