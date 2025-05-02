<?php

namespace App\Repositories\Contracts;

use App\Filters\CommentFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface CommentRepositoryInterface
{
    public function getFilteredWithRelations(CommentFilter $filter, int $perPage = 10): LengthAwarePaginator;
    public function findWithRelations(int $id): ?Model;
    public function isOwner(int $commentId, int $userId): bool;
    public function create(array $data): Model;
    public function delete(int $id): bool;
    public function update(int $id, array $data): bool;
} 