<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Filters\CommentFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentRepository extends BaseRepository
{
    /**
     * CommentRepository constructor.
     *
     * @param Comment $model
     */
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }

    public function getFilteredWithRelations(CommentFilter $filter, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        $filter->apply($query);
        
        return $query->with(['user', 'news'])->paginate($perPage);
    }

    public function getPaginatedWithRelations(int $perPage = 10): LengthAwarePaginator
    {
        return $this->withPaginate(['user', 'news'], $perPage);
    }

    public function findWithRelations(int $id): ?Comment
    {
        return $this->findWith($id, ['user', 'news']);
    }

    public function isOwner(int $commentId, int $userId): bool
    {
        return $this->model->where('id', $commentId)
            ->where('user_id', $userId)
            ->exists();
    }
} 