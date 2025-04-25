<?php

namespace App\Http\Resources\News;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "is_new" => $this->is_new,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'user' => new UserResource($this->user),
        ];
    }
}
