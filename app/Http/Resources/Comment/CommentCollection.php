<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\UserResource;

class CommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'user_id' => $comment->user_id,
                    'news_id' => $comment->news_id,
                    'created_at' => $comment->created_at,
                    'updated_at' => $comment->updated_at,
                    'user' => new UserResource($comment->user),
                ];
            }),
        ];
    }
}
