<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// ----------------------
// Landing / auth page
// ----------------------
Route::get('/', [AuthController::class, 'index'])->name('auth.page');
Route::post('/signup', [AuthController::class, 'store'])->name('students.store');
Route::post('/login', [AuthController::class, 'login'])->name('students.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('students.logout');

// ----------------------
// Feed routes
// ----------------------
Route::prefix('feed')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('feed.page');
    Route::get('/category', [PostController::class, 'category'])->name('category.page');

    Route::post('/posts/store', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])->name('posts.like');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/restore', [PostController::class, 'restore'])->name('posts.restore');
    Route::delete('/posts/{id}/force-delete', [PostController::class, 'forceDelete'])
    ->name('posts.forceDelete');
});

// ----------------------
// Profile routes
// ----------------------
Route::prefix('profile')->group(function () {
    Route::get('/', [PostController::class, 'profile'])->name('profile.page');
    Route::get('/archived', [PostController::class, 'archived'])->name('archived.page');
    
    Route::post('/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/student/change-password', [ProfileController::class, 'changePassword'])->name('student.changePassword');
});

Route::post('/posts/{post}/comment', [PostController::class, 'storeComment'])->name('posts.comment');
