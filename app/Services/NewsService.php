<?php

namespace App\Services;

use App\Models\News;

class NewsService
{
    protected $limit = 5;

    public function getNews()
    {
        return News::orderBy('created_at', 'desc')->paginate($this->limit);
    }


    public function getNewsById($id)
    {
        return News::find($id);
    }
}
