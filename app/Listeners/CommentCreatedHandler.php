<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommentNotification;
use App\Models\Comment;

class CommentCreatedHandler
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\CommentCreated  $event
     * @return void
     */
    public function handle(CommentCreated $event)
    {

        Log::info('Нова подія: створений коментар', [
            'comment_id' => $event->comment->id,
            'user_id' => $event->comment->user_id,
            'body' => $event->comment->body,
        ]);

        Mail::to(config('mail.moderator_email', 'moderator@example.com'))->send(new CommentNotification($event->comment));
    }

}
