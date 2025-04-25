<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/swagger', function () {
    return view('swagger');
});

Route::post('/login', [WebAuthController::class, 'login']);
Route::middleware(['auth'])->post('/refresh-session', [WebAuthController::class, 'refreshSession']);
Route::middleware(['auth'])->post('/logout', [WebAuthController::class, 'logout']);
