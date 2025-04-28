<?php

namespace App\Services;

use App\Repositories\CommentRepository;
use App\Filters\CommentFilter;
use App\Jobs\SendCommentNotification;
use App\Events\CommentCreated;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\Comment\CommentResource;

class CommentService
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

    public function storeComment($data)
    {

        $comment = $this->commentRepository->create($data);

        $comment->load('user', 'news.user');

        try {
            $commentResource = CommentResource::make($comment)->resolve();

            SendCommentNotification::dispatch($commentResource);
            broadcast(new CommentCreated($commentResource));
            
        } catch (\Exception $e) {
            report($e);
        }

        return $comment;
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
