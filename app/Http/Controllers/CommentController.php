<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\DeleteCommentRequest;
use App\Models\Comment;
use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\Comment\CommentResource;
use App\Filters\CommentFilter;
use Illuminate\Support\Facades\Log;
use App\Services\Contracts\CommentServiceInterface;
use App\Services\Contracts\NewsServiceInterface;

class CommentController extends Controller
{
    public function __construct(
        protected CommentServiceInterface $commentService,
        protected NewsServiceInterface $newsService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(CommentFilter $filter)
    {
        Log::info('Start Controller:CommentController.index', ['user_id' => auth()->id(), 'query' => request()->query()]);

        $comments = $this->commentService->getComments($filter);

        $result = CommentCollection::make($comments);

        Log::info('End Controller:CommentController.index', ['result' => $result]);

        return $result;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $user_id = Auth::id();

        Log::info('Start Controller:CommentController.store', ['user_id' => $user_id, 'request' => $request->all()]);

        $news = $this->newsService->getNewsById($request->news_id);
        if (!$news) {
            Log::info('End Controller:CommentController.store', ['result' => ['message' => 'News not found'], 404]);
            return response()->json(['message' => 'News not found'], 404);
        }

        $data = $request->validated();
        $data['user_id'] = $user_id;

        $comment = $this->commentService->storeComment($data);

        $result = CommentResource::make($comment);

        Log::info('End Controller:CommentController.store', ['result' => $result]);       

        return $result; 
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        Log::info('Start Controller:CommentController.show', ['comment' => $comment]);

        $result = CommentResource::make($comment);

        Log::info('End Controller:CommentController.show', ['result' => $result]);

        return $result;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {   
        Log::info('Start Controller:CommentController.update', ['user_id' => auth()->id(), 'comment' => $comment, 'request' => $request->all()]);

        $news = $this->newsService->getNewsById($request->news_id);
        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        $data = $request->validated();

        $comment = $this->commentService->updateComment($comment, $data);

        $result = CommentResource::make($comment);

        Log::info('End Controller:CommentController.update', ['result' => $result]);

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteCommentRequest $request, Comment $comment)
    {
        Log::info('Start Controller:CommentController.destroy', ['user_id' => auth()->id(), 'comment' => $comment]);

        $this->commentService->deleteComment($comment);

        Log::info('End Controller:CommentController.destroy', ['user_id' => auth()->id(), 'comment' => $comment]);

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
