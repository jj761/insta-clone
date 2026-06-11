<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\StoryController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/feed', [FeedController::class, 'index']);

    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    Route::post('/follow/{userId}', [FollowController::class, 'store']);
    Route::delete('/follow/{userId}', [FollowController::class, 'destroy']);

    Route::get('/users/search', [UserController::class, 'search']);
    Route::get('/users/{user}', [UserController::class, 'show']);

    Route::get('/stories', [StoryController::class, 'index']);
    Route::post('/stories', [StoryController::class, 'store']);
    Route::get('/stories/{story}', [StoryController::class, 'show']);
});
