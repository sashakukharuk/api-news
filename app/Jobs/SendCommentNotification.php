<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommentNotification;
use App\Models\Comment;

class SendCommentNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->comment->news->user; // get the news author
        Mail::to($user->email)->send(new CommentNotification($this->comment));
    }
}
