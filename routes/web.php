<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Landing / auth page
Route::group([], function () {
    Route::get('/', [AuthController::class, 'index'])->name('auth.page');
});

// Feed routes
Route::group(['prefix' => 'feed'], function () {
    Route::get('/', [AuthController::class, 'index'])->name('feed.page');
});

// Registration route
Route::post('/signup', [StudentController::class, 'store'])->name('students.store');
