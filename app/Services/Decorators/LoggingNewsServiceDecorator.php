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

    public function getNews()
    {
        Log::info('NewsService:getNews');
        return $this->service->getNews();
    }

    public function getNewsById($id)
    {
        Log::info('NewsService:getNewsById', ['id' => $id]);
        return $this->service->getNewsById($id);
    }
} 