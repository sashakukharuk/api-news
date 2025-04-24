<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\BroadcastableModelEventOccurred;
use App\Http\Resources\CommentResource;

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

    public function broadcastOn(string $event)
    {
        return [ 
            new PrivateChannel('news.'.$this->news_id)
        ];
    }

    public function broadcastWith()
    {       
        return CommentResource::make($this)->resolve();
    }

    protected function newBroadcastableEvent(string $event): BroadcastableModelEventOccurred
    {
        return (new BroadcastableModelEventOccurred(
            $this, $event
        ))->dontBroadcastToCurrentUser();
    }
}
