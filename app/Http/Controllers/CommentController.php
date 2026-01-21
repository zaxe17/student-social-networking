<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Return comments HTML for a specific post (for the comment modal).
     */
    public function index(Request $request, Post $post)
    {
        $comments = $post->comments()->with('author')->orderBy('created_at', 'asc')->get();

        $html = '';
        foreach ($comments as $comment) {
            $html .= view('component.comment', ['comment' => $comment])->render();
        }

        return response()->json([
            'success' => true,
            'comment_html' => $html,
            'comments_count' => $comments->count(),
        ]);
    }

    /**
     * Store new comment.
     * Returns JSON when called via fetch().
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => ['required', 'string'],
        ]);

        $studentId = $request->session()->get('student_id');

        $comment = PostComment::create([
            'post_id' => $post->post_id,
            'student_id' => $studentId,
            'content' => $request->content,
        ]);

        $comment->load('author');

        if ($request->expectsJson() || $request->ajax()) {
            $commentsCount = $post->comments()->count();

            return response()->json([
                'success' => true,
                'comment_html' => view('component.comment', ['comment' => $comment])->render(),
                'comments_count' => $commentsCount,
            ]);
        }

        return back();
    }
}
