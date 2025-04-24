<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\User;

class CommentService
{
    private $limit = 10;

    public function getComments()
    {
        return Comment::with('user')->paginate($this->limit);
    }

    public function getComment($id)
    {
        return Comment::with(User::class)->find($id);
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
