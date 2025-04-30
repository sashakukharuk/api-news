<?php

namespace App\Services\Contracts;

interface NewsServiceInterface
{
    public function getNews();
    public function getNewsById($id);
} 