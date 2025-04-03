<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/posts', [PostController::class, 'getAllPosts']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authenticated routes
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Post routes
    Route::get('/my-posts', [PostController::class, 'getUserPosts']);
    Route::post('/new-post', [PostController::class, 'createPost']);
    Route::put('/update-post/{id}', [PostController::class, 'updatePost']);
    Route::delete('/delete-post/{id}', [PostController::class, 'deletePost']);

    // Comment routes
    Route::post('/new-comment', [CommentController::class, 'addComment']);
    Route::put('/update-comment/{id}', [CommentController::class, 'updateComment']);
    Route::delete('/delete-comment/{id}', [CommentController::class, 'deleteComment']);
});
