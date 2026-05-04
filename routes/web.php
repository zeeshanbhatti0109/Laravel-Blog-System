<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('posts.index');
});

// Post routes using SLUG
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/{post:slug}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{post:slug}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{post:slug}', [PostController::class, 'destroy'])->name('posts.destroy');

// Comment routes
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

// User routes (for clickable author names)
Route::get('/users/{user:slug}/posts', [UserController::class, 'posts'])->name('users.posts');

// Tag routes (for clickable tags)
Route::get('/tags/{tag:slug}/posts', [TagController::class, 'posts'])->name('tags.posts');