<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Models\News;
use Illuminate\Routing\Controller;
use App\Http\Resources\News\NewsCollection;
use App\Http\Resources\News\NewsResource;
use Illuminate\Support\Facades\Log;
use App\Services\Contracts\NewsServiceInterface;

class NewsController extends Controller
{

    public function __construct(protected NewsServiceInterface $newsService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::info('Start Controller:NewsController.index', ['user_id' => auth()->id(), 'query' => request()->query()]);

        $news = $this->newsService->getNews();

        $result = NewsCollection::make($news);

        Log::info('End Controller:NewsController.index', ['news' => $result]);

        return $result;
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        Log::info('Start Controller:NewsController.show', ['news' => $news]);

        $result = NewsResource::make($news);

        Log::info('End Controller:NewsController.show', ['news' => $news]);

        return $result;
    }
}
