<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    $categories = \App\Models\PostCategory::orderBy('category_name')->get();
    $categoryId = $request->get('category_id');

    $posts = \App\Models\Post::with(['author', 'category', 'comments.author'])
        ->withCount(['likes', 'comments'])
        ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
        ->latest('created_at')
        ->get();

    return view('page.category', compact('posts', 'categories', 'categoryId'));

}

    public function archived()
{
    $categories = \App\Models\PostCategory::orderBy('category_name')->get();

    $posts = \App\Models\Post::onlyTrashed()
        ->with(['author', 'category', 'comments.author'])
        ->withCount(['likes', 'comments'])
        ->where('student_id', auth()->user()->student_id)
        ->latest('deleted_at')
        ->get();

    return view('page.archived', compact('posts', 'categories'));
}


    public function store(Request $request)
    {
        $request->validate([
            'content' => ['required', 'string'],
            'category_id' => ['nullable'],
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
            'content' => ['required', 'string'],
            'category_id' => ['nullable'],
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

        $post->delete(); // soft delete = archive
        return back()->with('success', 'Archived!');
    }

    public function restore(Post $post)
    {
        // route uses onlyTrashed binding, see routes section
        if ($post->student_id !== Auth::user()->student_id) abort(403);

        $post->restore();
        return back()->with('success', 'Restored!');
    }

    public function toggleLike(Post $post)
    {
        $sid = Auth::user()->student_id;

        $existing = PostLike::where('post_id', $post->post_id)
            ->where('student_id', $sid)
            ->first();

        if ($existing) $existing->delete();
        else PostLike::create(['post_id' => $post->post_id, 'student_id' => $sid]);

        return back();
    }
}
