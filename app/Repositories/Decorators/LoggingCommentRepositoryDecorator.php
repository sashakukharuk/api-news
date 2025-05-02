<?php

namespace App\Repositories\Decorators;

use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Filters\CommentFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class LoggingCommentRepositoryDecorator implements CommentRepositoryInterface
{
    private CommentRepositoryInterface $repository;

    public function __construct(CommentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getFilteredWithRelations(CommentFilter $filter, int $perPage = 10): LengthAwarePaginator
    {
        Log::info('CommentRepository:getFilteredWithRelations', ['filter' => $filter, 'perPage' => $perPage]);
        return $this->repository->getFilteredWithRelations($filter, $perPage);
    }

    public function findWithRelations(int $id): ?Model
    {
        Log::info('CommentRepository:findWithRelations', ['id' => $id]);
        return $this->repository->findWithRelations($id);
    }

    public function isOwner(int $commentId, int $userId): bool
    {
        Log::info('CommentRepository:isOwner', ['commentId' => $commentId, 'userId' => $userId]);
        return $this->repository->isOwner($commentId, $userId);
    }

    public function create(array $data): Model
    {
        Log::info('CommentRepository:create', ['data' => $data]);
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        Log::info('CommentRepository:update', ['id' => $id, 'data' => $data]);
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        Log::info('CommentRepository:delete', ['id' => $id]);
        return $this->repository->delete($id);
    }
} 