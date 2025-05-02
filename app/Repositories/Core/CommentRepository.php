<?php

namespace App\Repositories\Core;

use App\Models\Comment;
use App\Filters\CommentFilter;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contracts\CommentRepositoryInterface;

class CommentRepository implements CommentRepositoryInterface            
{
    protected Comment $model;   
    /**
     * CommentRepository constructor.
     *
     * @param Comment $model
     */     
    public function __construct(Comment $model)
    {
        $this->model = $model;
    }

    public function getFilteredWithRelations(CommentFilter $filter, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        $filter->apply($query);
        
        return $query->with(['user'])->paginate($perPage);
    }

    public function findWithRelations(int $id): ?Comment
    {
        return $this->model->with(['user', 'news.user'])->find($id);
    }

    public function isOwner(int $commentId, int $userId): bool
    {
        return $this->model->where('id', $commentId)
            ->where('user_id', $userId)
            ->exists();
    }

    public function create(array $data): Comment
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id);
    }
} 