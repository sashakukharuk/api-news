<?php

namespace App\Services\Decorators;

use Illuminate\Support\Facades\Log;
use App\Services\Contracts\NewsServiceInterface;

class LoggingNewsServiceDecorator implements NewsServiceInterface
{
    private NewsServiceInterface $service;

    public function __construct(NewsServiceInterface $service)
    {
        $this->service = $service;
    }

    public function __call(string $method, array $arguments)
    {
        Log::info("Start Service:NewsService.{$method}", ['arguments' => $arguments]);

        $result = call_user_func_array([$this->service, $method], $arguments);

        Log::info("End Service:NewsService.{$method}", ['result' => $result]);  

        return $result;
    }

    public function getNews()
    {
        return $this->__call(__FUNCTION__, []);
    }

    public function getNewsById($id)
    {
        return $this->__call(__FUNCTION__, [$id]);
    }
} 