<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CommentController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('multi.auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::prefix('news')->controller(NewsController::class)->group(function () {
    Route::get('', 'index')->name('news.index');   
    Route::get('{news}', 'show')->name('news.show');
});

Route::prefix('comments')->controller(CommentController::class)->group(function () {
    Route::get('', 'index')->name('comments.index');   
    Route::get('{comment}', 'show')->name('comments.show');
    Route::middleware('multi.auth')->group(function () {
        Route::post('', 'store')->name('comments.store');
        Route::put('{comment}', 'update')->name('comments.update');
        Route::delete('{comment}', 'destroy')->name('comments.destroy');
    });
});


