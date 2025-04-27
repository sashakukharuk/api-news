<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\DeleteCommentRequest;
use App\Models\Comment;
use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\Comment\CommentResource;
use App\Services\CommentService;
use App\Services\NewsService;
use App\Filters\CommentFilter;

class CommentController extends Controller
{
    public function __construct(
        protected CommentService $commentService,
        protected NewsService $newsService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(CommentFilter $filter)
    {
        return CommentCollection::make($this->commentService->getComments($filter));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $user_id = Auth::id();

        $news = $this->newsService->getNews($request->news_id);
        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        $data = $request->validated();
        $data['user_id'] = $user_id;

        $comment = $this->commentService->storeComment($data);

        return CommentResource::make($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return CommentResource::make($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $news = $this->newsService->getNews($request->news_id);
        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        $data = $request->validated();

        $comment = $this->commentService->updateComment($comment, $data);

        return CommentResource::make($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteCommentRequest $request, Comment $comment)
    {
        $this->commentService->deleteComment($comment);

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
