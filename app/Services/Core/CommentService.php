<?php

namespace App\Services\Core;

use App\Repositories\CommentRepository;
use App\Filters\CommentFilter;
use App\Jobs\SendCommentNotification;
use App\Events\CommentCreated;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\Comment\CommentResource;
use App\Services\Contracts\CommentServiceInterface;

class CommentService implements CommentServiceInterface
{
    private $limit = 10;
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getComments(CommentFilter $filter)
    {
        return $this->commentRepository->getFilteredWithRelations($filter, $this->limit);
    }

    public function getComment($id)
    {
        return $this->commentRepository->findWithRelations($id);
    }

    public function storeComment(array $data): \App\Models\Comment
    {

        $comment = $this->commentRepository->create($data);

        $this->notifyAboutComment($comment);

        return $comment;
    }

    private function notifyAboutComment($comment): void
    {
        try {
            SendCommentNotification::dispatch($comment);
            broadcast(new CommentCreated($comment));
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function deleteComment($comment)
    {
        return $this->commentRepository->delete($comment->id);
    }

    public function updateComment($comment, $data)
    {
        $id = $comment->id;
        $this->commentRepository->update($id, $data);

        return $this->commentRepository->findWithRelations($id);
    }

    public function isOwner($commentId, $userId)
    {
        return $this->commentRepository->isOwner($commentId, $userId);
    }
}
