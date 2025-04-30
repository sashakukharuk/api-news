<?php

namespace App\Services\Core;
    
use App\Repositories\NewsRepository;
use App\Services\Contracts\NewsServiceInterface;

class NewsService implements NewsServiceInterface
{
    protected $limit = 5;
    protected NewsRepository $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function getNews()
    {
        return $this->newsRepository->getPaginatedWithUser($this->limit);
    }

    public function getNewsById($id)
    {
        return $this->newsRepository->findWithUser($id);
    }
}
