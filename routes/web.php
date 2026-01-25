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
    Route::get('/category/{category_id}', [PostController::class, 'category']);
    
    // Hashtags
    Route::get('/hashtag', [PostController::class, 'hashtag'])->name('hashtag.page');
    
    // Post actions
    Route::prefix('posts')->group(function () {
        Route::post('/store', [PostController::class, 'store'])->name('posts.store');
        Route::post('/{post}/like', [PostController::class, 'toggleLike'])->name('posts.like');
        Route::put('/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::post('/{post}/restore', [PostController::class, 'restore'])->name('posts.restore');
        Route::delete('/{id}/force-delete', [PostController::class, 'forceDelete'])->name('posts.forceDelete');
        Route::post('/{post}/report', [PostController::class, 'reportPost'])->name('posts.report');
        Route::get('/{post}/reactors', [PostController::class, 'reactors']);
        Route::get('/{post}/comments', [CommentController::class, 'index']);
        Route::post('/{post}/comments', [CommentController::class, 'store']);
    });
    
    // Comments
    Route::prefix('comments')->group(function () {
        Route::put('/{comment}', [CommentController::class, 'update'])->name('comments.update');
        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    });
});

// ----------------------
// Profile Routes
// ----------------------
Route::prefix('profile')->group(function () {
    Route::get('/', [PostController::class, 'profile'])->name('profile.page');
    Route::get('/archived', [PostController::class, 'archived'])->name('archived.page');
    Route::post('/update', [ProfileController::class, 'update'])->name('profile.update');
    
    // Student actions
    Route::prefix('student')->group(function () {
        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('student.changePassword');
        Route::post('/validate-old-password', [ProfileController::class, 'validateOldPassword'])->name('student.validateOldPassword');
        Route::delete('/delete', [ProfileController::class, 'deleteAccount'])->name('student.delete');
    });
    
    // View another student's profile (must be last to avoid conflicts)
    Route::get('/{student_id}', [PostController::class, 'viewProfile'])->name('profile.view');
});

// ----------------------
// AJAX / Search Routes
// ----------------------
Route::prefix('search')->group(function () {
    Route::get('/', [SearchController::class, 'search'])->name('search');
    Route::get('/results', [SearchController::class, 'searchResults'])->name('search.results');
});

// ----------------------
// Admin Routes
// ----------------------
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    // Admin Actions
    Route::prefix('reports')->group(function () {
        Route::delete('/{id}', [AdminAuthController::class, 'deleteReport'])->name('admin.reports.delete');
        Route::delete('/{id}/post', [AdminAuthController::class, 'deletePost'])->name('admin.posts.delete');
    });
});

// ----------------------
// Event Routes
// ----------------------
Route::prefix('event')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('events.index');
    Route::post('/', [EventController::class, 'store'])->name('events.store');
});