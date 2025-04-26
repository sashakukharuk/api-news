<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use App\Services\CommentService;

class CommentPolicy
{
    private CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $this->commentService->isOwner($comment->id, $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $this->commentService->isOwner($comment->id, $user->id);
    }
}
