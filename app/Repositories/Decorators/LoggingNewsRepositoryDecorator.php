<?php

namespace App\Repositories\Decorators;

use App\Repositories\Contracts\NewsRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class LoggingNewsRepositoryDecorator implements NewsRepositoryInterface
{
    private NewsRepositoryInterface $repository;

    public function __construct(NewsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginatedWithUser(int $perPage = 10): LengthAwarePaginator
    {
        Log::info('NewsRepository:getPaginatedWithUser', ['perPage' => $perPage]);
        return $this->repository->getPaginatedWithUser($perPage);
    }

    public function findWithUser(int $id): ?Model
    {
        Log::info('NewsRepository:findWithUser', ['id' => $id]);
        return $this->repository->findWithUser($id);
    }
} 