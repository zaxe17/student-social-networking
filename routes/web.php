<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('page.main');
});

Route::get('/feed', function () {
    return view('page.feed');
})->name('feed.page');