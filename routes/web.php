<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::get('/', [AuthController::class, 'index'])->name('auth.page');
});

Route::group(['prefix' => 'feed'], function () {
    Route::get('/', [AuthController::class, 'index'])->name('feed.page');
});