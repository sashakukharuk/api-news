<?php

namespace App\Services\Decorators;

use Illuminate\Support\Facades\Log;
use App\Filters\CommentFilter;
use App\Services\Contracts\CommentServiceInterface;

class LoggingCommentServiceDecorator implements CommentServiceInterface
{
    private CommentServiceInterface $service;

    public function __construct(CommentServiceInterface $service)
    {
        $this->service = $service;
    }

    public function getComments(CommentFilter $filter)
    {
        Log::info('CommentService:getComments', ['filter' => $filter]);
        return $this->service->getComments($filter);
    }

    public function getComment($id)
    {
        Log::info('CommentService:getComment', ['id' => $id]);
        return $this->service->getComment($id);
    }

    public function storeComment(array $data): \App\Models\Comment
    {
        Log::info('CommentService:storeComment', ['data' => $data]);
        return $this->service->storeComment($data);
    }

    public function deleteComment($comment)
    {
        Log::info('CommentService:deleteComment', ['comment' => $comment]);
        return $this->service->deleteComment($comment);
    }

    public function updateComment($comment, $data)
    {
        Log::info('CommentService:updateComment', ['comment' => $comment, 'data' => $data]);
        return $this->service->updateComment($comment, $data);
    }

    public function isOwner($commentId, $userId)
    {
        Log::info('CommentService:isOwner', ['commentId' => $commentId, 'userId' => $userId]);
        return $this->service->isOwner($commentId, $userId);
    }
} 