<?php

namespace App\Services;

use App\Models\Comment;
use App\Filters\CommentFilter;

class CommentService
{
    private $limit = 10;

    public function getComments(CommentFilter $filter)
    {
        return Comment::with('user')->filter($filter)->paginate($this->limit);
    }

    public function getComment($id)
    {
        return Comment::with('user')->find($id);
    }

    public function storeComment($data)
    {
        return Comment::create($data);
    }

    public function deleteComment(Comment $comment)
    {
        $comment->delete();
    }

    public function updateComment(Comment $comment, $data)  
    {
        $comment->update($data);

        return $comment;
    }
}
