<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'book_title',
        'rating',
        'comment'
    ];

    // En recension tillhör en användare
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
