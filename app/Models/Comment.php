<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    protected $fillable = [
        'body',
        'user_id',
        'news_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
