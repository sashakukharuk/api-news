<?php

namespace App\Services;

use App\Repositories\CommentRepository;
use App\Filters\CommentFilter;

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
        return $this->commentRepository->create($data);
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
