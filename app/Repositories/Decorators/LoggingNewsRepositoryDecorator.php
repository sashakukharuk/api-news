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

    public function __call(string $method, array $arguments)
    {
        Log::info("Start Repository:NewsRepository.{$method}", ['arguments' => $arguments]);

        $result = call_user_func_array([$this->repository, $method], $arguments);   

        Log::info("End Repository:NewsRepository.{$method}", ['result' => $result]);

        return $result;
    }   

    public function getPaginatedWithUser(int $perPage = 10): LengthAwarePaginator
    {
        return $this->__call(__FUNCTION__, [$perPage]);
    }

    public function findWithUser(int $id): ?Model
    {
        return $this->__call(__FUNCTION__, [$id]);
    }
} 