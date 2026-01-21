<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostLike;

class PostController extends Controller
{
    public function index()
    {
        $categories = PostCategory::orderBy('category_name')->get();
        $posts = Post::with(['author', 'category', 'comments.author'])
            ->withCount(['likes', 'comments'])
            ->latest('created_at')
            ->get();
        return view('page.feed', compact('posts', 'categories'));
    }

    public function category(Request $request)
    {
        $categories = PostCategory::orderBy('category_name')->get();
        $categoryId = $request->get('category_id');

        $posts = Post::with(['author', 'category', 'comments.author'])
            ->withCount(['likes', 'comments'])
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->latest('created_at')
            ->get();

        return view('page.category', compact('posts', 'categories', 'categoryId'));
    }

<<<<<<< HEAD
}

=======
>>>>>>> 576b130f17ed2b770bae503783f15073a110f12d
    public function archived()
    {
        $categories = PostCategory::orderBy('category_name')->get();
        $posts = Post::onlyTrashed()
            ->with(['author', 'category', 'comments.author'])
            ->withCount(['likes', 'comments'])
            ->where('student_id', Auth::user()->student_id)
            ->latest('deleted_at')
            ->get();
        return view('page.archived', compact('posts', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'category_id' => 'nullable|exists:post_categories,category_id',
        ]);

        Post::create([
            'student_id' => Auth::user()->student_id,
            'content' => $request->content,
            'category_id' => $request->category_id,
        ]);

        return back()->with('success', 'Posted!');
    }

    public function update(Request $request, Post $post)
    {
        if ($post->student_id !== Auth::user()->student_id) abort(403);

        $request->validate([
            'content' => 'required|string',
            'category_id' => 'nullable|exists:post_categories,category_id',
        ]);

        $post->update([
            'content' => $request->content,
            'category_id' => $request->category_id,
        ]);

        return back()->with('success', 'Updated!');
    }

    public function destroy(Post $post)
    {
        if ($post->student_id !== Auth::user()->student_id) abort(403);
        $post->delete();
        return back()->with('success', 'Archived!');
    }

    public function restore(Post $post)
    {
        if ($post->student_id !== Auth::user()->student_id) abort(403);
        $post->restore();
        return back()->with('success', 'Restored!');
    }

    public function toggleLike(Post $post)
    {
        $studentId = Auth::user()->student_id;

        $existing = PostLike::where('post_id', $post->post_id)
            ->where('student_id', $studentId)
            ->first();

        if ($existing) $existing->delete();
        else PostLike::create([
            'post_id' => $post->post_id,
            'student_id' => $studentId,
        ]);

        return back();
    }
}
