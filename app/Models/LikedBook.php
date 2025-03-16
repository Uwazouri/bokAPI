<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LikedBook extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'title',
        'thumbnail'
    ];
}
