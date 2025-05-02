<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface NewsRepositoryInterface
{
    public function getPaginatedWithUser(int $perPage = 10): LengthAwarePaginator;
    public function findWithUser(int $id): ?Model;
} 