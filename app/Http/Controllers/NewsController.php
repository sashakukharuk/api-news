<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Models\News;
use Illuminate\Routing\Controller;
use App\Services\NewsService;
use App\Http\Resources\News\NewsCollection;
use App\Http\Resources\News\NewsResource;

class NewsController extends Controller
{

    public function __construct(protected NewsService $newsService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return NewsCollection::make($this->newsService->getNews());
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        return NewsResource::make($news);
    }
}
