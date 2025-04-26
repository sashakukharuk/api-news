<?php

namespace App\Services;

use App\Repositories\NewsRepository;

class NewsService
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
