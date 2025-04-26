<?php

namespace App\Repositories;

use App\Models\News;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsRepository extends BaseRepository
{
    /**
     * NewsRepository constructor.
     *
     * @param News $model
     */
    public function __construct(News $model)
    {
        parent::__construct($model);
    }

    public function getPaginatedWithUser(int $perPage = 10): LengthAwarePaginator
    {
        return $this->withPaginate(['user'], $perPage);
    }

    public function findWithUser(int $id): ?News
    {
        return $this->findWith($id, ['user']);
    }
} 