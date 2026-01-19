<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index'])->name('auth.page');

// avoid MethodNotAllowed when typing /signin or /signup
Route::get('/signin', fn() => redirect()->route('auth.page'));
Route::get('/signup', fn() => redirect()->route('auth.page'));

Route::post('/signin', [AuthController::class, 'login'])->name('students.login');
Route::post('/signup', [AuthController::class, 'store'])->name('students.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('students.logout');

// pages
Route::get('/feed', [PostController::class, 'index'])->name('feed.page');
Route::get('/feed/category', [PostController::class, 'category'])->name('category.page');
Route::get('/archived', [PostController::class, 'archived'])->name('archived.page');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile.page');

// actions
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::patch('/posts/{post:post_id}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{post:post_id}', [PostController::class, 'destroy'])->name('posts.destroy');

Route::post('/posts/{post:post_id}/like', [PostController::class, 'toggleLike'])->name('posts.like');
Route::post('/posts/{post:post_id}/comments', [CommentController::class, 'store'])->name('comments.store');

// restore soft-deleted post (since route-model binding won't find trashed)
Route::post('/posts/{post_id}/restore', function ($post_id) {
    $post = \App\Models\Post::onlyTrashed()->where('post_id', $post_id)->firstOrFail();
    return app(\App\Http\Controllers\PostController::class)->restore($post);
})->name('posts.restore');

Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');



