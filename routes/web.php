<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

// ----------------------
// Landing / Auth Pages
// ----------------------
Route::get('/', [AuthController::class, 'index'])->name('auth.page');
Route::post('/signup', [AuthController::class, 'store'])->name('students.store');
Route::post('/login', [AuthController::class, 'login'])->name('students.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('students.logout');

// ----------------------
// Feed Routes
// ----------------------
Route::prefix('feed')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('feed.page');

    // Category routes
    Route::get('/category', [PostController::class, 'category'])->name('category.page');
    Route::get('/category/{category_id}', [PostController::class, 'category']); // optional parameter version

    // Post actions
    Route::post('/posts/store', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])->name('posts.like');
    Route::put('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/restore', [PostController::class, 'restore'])->name('posts.restore');
    Route::delete('/posts/{id}/force-delete', [PostController::class, 'forceDelete'])->name('posts.forceDelete');
    Route::post('/posts/{post}/report', [PostController::class, 'reportPost'])->name('posts.report');

    // Comments
    Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Reactors
    Route::get('/posts/{post}/reactors', [PostController::class, 'reactors']);

    // Hashtags
    Route::get('/hashtag', [PostController::class, 'hashtag'])->name('hashtag.page');

});

// ----------------------
// Profile Routes
// ----------------------
Route::prefix('profile')->group(function () {
    Route::get('/', [PostController::class, 'profile'])->name('profile.page');
    Route::get('/archived', [PostController::class, 'archived'])->name('archived.page');

    Route::post('/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/student/change-password', [ProfileController::class, 'changePassword'])->name('student.changePassword');
    Route::post('/student/validate-old-password', [ProfileController::class, 'validateOldPassword'])->name('student.validateOldPassword');
    // View another student's profile
    Route::get('/{student_id}', [PostController::class, 'viewProfile'])->name('profile.view');
    // Delete student account
    Route::delete('/student/delete', [ProfileController::class, 'deleteAccount'])->name('student.delete');
});

// ----------------------
// AJAX / Search Routes
// ----------------------
Route::prefix('search')->group(function () {
    Route::get('/', [SearchController::class, 'search'])->name('search');           // main search
    Route::get('/results', [SearchController::class, 'searchResults'])->name('search.results'); // search results
});

// ----------------------
// Admin Routes
// ----------------------
Route::get('/admin', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// ----------------------
// Event Routes
// ----------------------
Route::get('/event', [EventController::class, 'index'])->name('events.index');
Route::post('/event', [EventController::class, 'store'])->name('events.store');