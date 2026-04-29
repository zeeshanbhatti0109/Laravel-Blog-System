<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// Home page - redirect to posts index (which has all data)
Route::get('/', function () {
    return redirect()->route('posts.index');
});

// Resource controller for posts
Route::resource('posts', PostController::class);

// Comment routes - CORRECTED
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');