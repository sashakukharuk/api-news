<?php

namespace App\Repositories\Core;

use App\Models\News;
use Illuminate\Pagination\LengthAwarePaginator; 
use App\Repositories\Contracts\NewsRepositoryInterface;

class NewsRepository implements NewsRepositoryInterface  
{
    protected News $model;
    /**
     * NewsRepository constructor.
     *
     * @param News $model
     */
    public function __construct(News $model)
    {
        $this->model = $model;
    }

    public function getPaginatedWithUser(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with('user')->paginate($perPage);
    }

    public function findWithUser(int $id): ?News
    {
        return $this->model->with('user')->find($id);
    }
} 