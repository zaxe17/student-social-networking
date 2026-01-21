<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostLike;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $postModel = new Post();
        $categoryModel = new PostCategory();

        $studentId = $request->session()->get('student_id');
        $student = $this->student($studentId);

        $categories = $categoryModel->orderBy('category_name')->get();

        $posts = $postModel->with(['author', 'category', 'comments.author'])
            ->withCount(['likes', 'comments'])
            ->latest('created_at')
            ->get();

        return view('page.feed', compact('posts', 'categories', 'student'));
    }

    public function profile(Request $request)
    {
        $postModel = new Post();
        $categoryModel = new PostCategory();

        $studentId = $request->session()->get('student_id');
        $student = $this->student($studentId);

        $categories = $categoryModel->orderBy('category_name')->get();

        $posts = $postModel->with(['author', 'category', 'comments.author'])
            ->withCount(['likes', 'comments'])
            ->where('student_id', $studentId)
            ->latest('created_at')
            ->get();

        return view('page.profile', compact('posts', 'categories', 'student'));
    }

    public function archived(Request $request)
    {
        $postModel = new Post();
        $categoryModel = new PostCategory();

        $studentId = $request->session()->get('student_id');
        $student = $this->student($studentId);

        $categories = $categoryModel->orderBy('category_name')->get();

        $posts = $postModel->onlyTrashed()
            ->with(['author', 'category', 'comments.author'])
            ->withCount(['likes', 'comments'])
            ->where('student_id', $studentId)
            ->latest('deleted_at')
            ->get();

        return view('page.archived', compact('posts', 'categories', 'student'));
    }

    public function forceDelete($id, Request $request)
    {
        $postModel = new Post();

        $studentId = $request->session()->get('student_id');

        $post = $postModel->withTrashed()
            ->where('post_id', $id)
            ->where('student_id', $studentId)
            ->firstOrFail();

        Log::info("Post id: " . $post->post_id);
        Log::info("Student id: " . $post->student_id);
        Log::info("Post content: " . $post->content);

        $post->forceDelete();

        return redirect()->back()->with('success', 'Post permanently deleted.');
    }

    public function store(Request $request)
    {
        $postModel = new Post();

        $studentId = $request->session()->get('student_id');

        $request->validate([
            'content' => 'required|string',
            'category_id' => 'nullable|exists:post_categories,category_id',
        ]);

        $postModel->student_id = $studentId;
        $postModel->content = $request->content;
        $postModel->category_id = $request->category_id;

        $postModel->save();

        return back()->with('success', 'Post created!');
    }

    public function destroy(Post $post, Request $request)
    {
        $studentId = $request->session()->get('student_id');

        if ($post->student_id !== $studentId) {
            abort(403);
        }

        $post->delete();
        return back()->with('success', 'Post archived');
    }

    public function restore($postId, Request $request)
    {
        $postModel = new Post();

        $studentId = $request->session()->get('student_id');

        $post = $postModel->withTrashed()->findOrFail($postId);

        if ($post->student_id !== $studentId) {
            abort(403);
        }

        $post->restore();
        return back()->with('success', 'Post restored');
    }

    public function category(Request $request)
    {
        $postModel = new Post();
        $categoryModel = new PostCategory();

        $studentId = $request->session()->get('student_id');
        $student = $this->student($studentId);

        $categories = $categoryModel->orderBy('category_name')->get();

        $selectedCategories = $request->query('category', []);

        if (empty($selectedCategories)) {
            return redirect()->route('feed.page');
        }

        $posts = $postModel->with(['author', 'category', 'comments.author'])
            ->withCount(['likes', 'comments'])
            ->whereIn('category_id', $selectedCategories)
            ->latest('created_at')
            ->get();

        return view('page.category', compact('posts', 'categories', 'student', 'selectedCategories'));
    }






    public function toggleLike(Post $post, Request $request)
    {
        $studentId = $request->session()->get('student_id');
        if (!$studentId) return back()->with('error', 'Student not logged in.');

        $existingLike = PostLike::where('post_id', $post->id)
            ->where('student_id', $studentId)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            PostLike::create([
                'post_id' => $post->id,
                'student_id' => $studentId,
            ]);
        }

        return back();
    }

    private function student($studentId)
    {
        $studentModel = new Student();
        $student = $studentId ? $studentModel->find($studentId) : null;

        return $student;
    }
}