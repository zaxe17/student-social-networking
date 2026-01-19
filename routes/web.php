<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

// PUBLIC ROUTES
Route::get('/', [AuthController::class, 'index'])->name('auth.page');
Route::post('/signin', [AuthController::class, 'login'])->name('students.login');
Route::post('/signup', [AuthController::class, 'store'])->name('students.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('students.logout');

// FEED & PROFILE ROUTES (guests can view profile, feed requires login)
Route::get('/feed', [PostController::class, 'index'])->name('feed.page');
Route::get('/feed/category', [PostController::class, 'category'])->name('category.page');
Route::get('/archived', [PostController::class, 'archived'])->name('archived.page');

// PROFILE (guest view allowed, update/password require auth)
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.page');
Route::middleware('auth')->group(function() {
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
});

// POSTS
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::patch('/posts/{post:post_id}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{post:post_id}', [PostController::class, 'destroy'])->name('posts.destroy');
Route::post('/posts/{post:post_id}/like', [PostController::class, 'toggleLike'])->name('posts.like');
Route::post('/posts/{post:post_id}/comments', [CommentController::class, 'store'])->name('comments.store');

Route::post('/posts/{post_id}/restore', function ($post_id) {
    $post = Post::onlyTrashed()->where('post_id', $post_id)->firstOrFail();
    return app(PostController::class)->restore($post);
})->name('posts.restore');
