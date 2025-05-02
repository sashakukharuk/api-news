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

    public function __call(string $method, array $arguments)
    {
        Log::info("Start Service:CommentService.{$method}", ['arguments' => $arguments]);

        $result = call_user_func_array([$this->service, $method], $arguments);

        Log::info("End Service:CommentService.{$method}", ['result' => $result]);

        return $result;
    }

    public function getComments(CommentFilter $filter)
    {
        return $this->__call(__FUNCTION__, [$filter]);
    }

    public function getComment($id)
    {
        return $this->__call(__FUNCTION__, [$id]);
    }

    public function storeComment(array $data): \App\Models\Comment
    {
        return $this->__call(__FUNCTION__, [$data]);
    }

    public function deleteComment($comment)
    {
        return $this->__call(__FUNCTION__, [$comment]);
    }

    public function updateComment($comment, $data)
    {
        return $this->__call(__FUNCTION__, [$comment, $data]);
    }

    public function isOwner($commentId, $userId)
    {
        return $this->__call(__FUNCTION__, [$commentId, $userId]);
    }
} 