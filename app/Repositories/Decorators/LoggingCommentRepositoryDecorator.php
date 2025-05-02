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

    public function __call(string $method, array $arguments)
    {
        Log::info("Start Repository:CommentRepository.{$method}", ['arguments' => $arguments]);

        $result = call_user_func_array([$this->repository, $method], $arguments);   

        Log::info("End Repository:CommentRepository.{$method}", ['result' => $result]);

        return $result;
    }

    public function getFilteredWithRelations(CommentFilter $filter, int $perPage = 10): LengthAwarePaginator
    {
        return $this->__call(__FUNCTION__, [$filter, $perPage]);
    }

    public function findWithRelations(int $id): ?Model
    {
        return $this->__call(__FUNCTION__, [$id]);
    }

    public function isOwner(int $commentId, int $userId): bool
    {
        return $this->__call(__FUNCTION__, [$commentId, $userId]);
    }

    public function create(array $data): Model
    {
        return $this->__call(__FUNCTION__, [$data]);
    }

    public function update(int $id, array $data): bool
    {
        return $this->__call(__FUNCTION__, [$id, $data]);
    }

    public function delete(int $id): bool
    {
        return $this->__call(__FUNCTION__, [$id]);
    }
} 