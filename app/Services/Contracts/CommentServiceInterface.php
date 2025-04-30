<?php

namespace App\Services\Contracts;

use App\Filters\CommentFilter;

interface CommentServiceInterface
{
    public function getComments(CommentFilter $filter);
    public function getComment($id);
    public function storeComment(array $data): \App\Models\Comment;
    public function deleteComment($comment);
    public function updateComment($comment, $data);
    public function isOwner($commentId, $userId);
} 