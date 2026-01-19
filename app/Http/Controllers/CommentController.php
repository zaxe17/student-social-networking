<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => ['required', 'string'],
        ]);

        PostComment::create([
            'post_id' => $post->post_id,
            'student_id' => Auth::user()->student_id,
            'content' => $request->content,
        ]);

        return back();
    }
}
