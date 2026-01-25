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
        $comments = $post->comments()
            ->with('author')
            ->orderBy('created_at', 'asc')
            ->get();

        $html = '';
        foreach ($comments as $comment) {
            $html .= view('component.comment', ['comment' => $comment])->render();
        }

        return response()->json([
            'success'        => true,
            'comment_html'   => $html,
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
            'post_id'    => $post->post_id,
            'student_id' => $studentId,
            'content'    => $request->content,
        ]);

        $comment->load('author');

        if ($request->expectsJson() || $request->ajax()) {
            $commentsCount = $post->comments()->count();

            return response()->json([
                'success'        => true,
                'comment_html'   => view('component.comment', ['comment' => $comment])->render(),
                'comments_count' => $commentsCount,
            ]);
        }

        return back();
    }

    /**
     * Update an existing comment (owner only)
     */
    public function update(Request $request, PostComment $comment)
    {
        $studentId = $request->session()->get('student_id');

        if (!$studentId || (string)$comment->student_id !== (string)$studentId) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $request->validate([
            'content' => ['required', 'string'],
        ]);

        $comment->content = $request->content;
        $comment->save();

        $comment->load('author');

        return response()->json([
            'success'    => true,
            'comment_id' => $comment->comment_id ?? $comment->id, // supports custom PK
            'content'    => $comment->content,
        ]);
    }

    /**
     * Delete a comment (owner only)
     */
    public function destroy(PostComment $comment, Request $request)
    {
        $studentId = $request->session()->get('student_id');

        // Only owner can delete
        if (!$studentId || (string)$comment->student_id !== (string)$studentId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $postId = $comment->post_id;

        $comment->delete();

        $commentsCount = PostComment::where('post_id', $postId)->count();

        return response()->json([
            'success'        => true,
            'post_id'        => $postId,
            'comments_count' => $commentsCount,
        ]);
    }
}
