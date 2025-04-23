<?php

namespace App\Http\Resources\News;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NewsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "data" => $this->collection->map(function ($news) {
                return [
                    "id" => $news->id,
                    "title" => $news->title,
                    "description" => $news->description,
                    "created_at" => $news->created_at,
                    "updated_at" => $news->updated_at
                ];
            })
        ];
    }
}
