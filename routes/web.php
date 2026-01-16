<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Landing / auth page
Route::group([], function () {
    Route::get('/', [AuthController::class, 'index'])->name('auth.page');
    // Registration route
    Route::post('/signup', [AuthController::class, 'store'])->name('students.store');
});

// Feed routes
Route::group(['prefix' => 'feed'], function () {
    Route::get('/', [PostController::class, 'index'])->name('feed.page');
});

// Profile routes
Route::group(['prefix' => 'profile'], function () {
    Route::get('/', [ProfileController::class, 'index'])->name('profile.page');
});
