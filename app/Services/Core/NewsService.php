<?php

namespace App\Services\Core;
    
use App\Repositories\Contracts\NewsRepositoryInterface;
use App\Services\Contracts\NewsServiceInterface;

class NewsService implements NewsServiceInterface
{
    protected $limit = 5;
    protected NewsRepositoryInterface $newsRepository;

    public function __construct(NewsRepositoryInterface $newsRepository)
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
