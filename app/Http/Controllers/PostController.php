<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return view('page.feed');
    }

    public function category()
    {
        return view('page.category');
    }
}
