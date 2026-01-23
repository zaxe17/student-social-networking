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
    // ----------------------
    // Feed page
    // ----------------------
    public function index(Request $request) // Feed page
    {
        $studentId = $request->session()->get('student_id');
        $loggedInStudent = $this->student($studentId);

        if (!$loggedInStudent) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        $categories = PostCategory::orderBy('category_name')->get();

        $posts = Post::with(['author', 'category', 'comments.author', 'likes'])
            ->withCount(['likes', 'comments'])
            ->latest('created_at')
            ->get();

        return view('page.feed', [
            'posts' => $posts,
            'categories' => $categories,
            'student' => $loggedInStudent,
            'loggedInStudent' => $loggedInStudent,
            'loggedInStudentId' => $loggedInStudent->student_id,
        ]);
    }

    // ----------------------
    // Logged-in student's profile
    // ----------------------
    public function profile(Request $request)
    {
        $postModel = new Post();
        $categoryModel = new PostCategory();

        $studentId = $request->session()->get('student_id');
        $loggedInStudent = $this->student($studentId);

        $categories = $categoryModel->orderBy('category_name')->get();

        $posts = $postModel->with([
            'author',
            'category',
            'comments.author',
            'likes'
        ])
            ->withCount(['likes', 'comments'])
            ->where('student_id', $studentId)
            ->latest('created_at')
            ->get();

        return view('page.profile', [
            'student' => $loggedInStudent, // profile owner = logged-in student
            'posts' => $posts,
            'categories' => $categories,
            'loggedInStudent' => $loggedInStudent, // <--- pass full object
            'loggedInStudentId' => $loggedInStudent->student_id,
        ]);
    }

    public function category(Request $request)
    {
        $studentId = $request->session()->get('student_id');
        $loggedInStudent = $this->student($studentId);

        // Accept multiple selected categories
        $selectedCategories = $request->input('category', []); // returns array of IDs

        $categories = PostCategory::orderBy('category_name')->get();

        $posts = Post::with(['author', 'category', 'comments.author', 'likes'])
            ->withCount(['likes', 'comments'])
            ->whereIn('category_id', $selectedCategories)
            ->latest('created_at')
            ->get();

        return view('page.feed', [
            'posts' => $posts,
            'categories' => $categories,
            'selectedCategories' => $selectedCategories,
            'loggedInStudent' => $loggedInStudent,
            'student' => $loggedInStudent,
        ]);
    }

    // ----------------------
    // View another student's profile
    // ----------------------
    public function viewProfile($student_id, Request $request)
    {
        // Profile being viewed
        $student = Student::where('student_id', $student_id)->firstOrFail();

        // Logged-in student
        $loggedInStudentId = $request->session()->get('student_id');
        $loggedInStudent = $this->student($loggedInStudentId); // full model

        // Fetch posts of the profile being viewed
        $posts = $student->posts()->with([
            'author',
            'category',
            'comments.author',
            'likes'
        ])->withCount(['likes', 'comments'])
            ->latest('created_at')
            ->get();

        $categories = PostCategory::all();

        return view('page.profile', [
            'student' => $student,                     // profile being viewed
            'posts' => $posts,
            'categories' => $categories,
            'loggedInStudent' => $loggedInStudent,    // full model for sidebar
            'loggedInStudentId' => $loggedInStudentId, // id for edit button check
        ]);
    }

    // ----------------------
    // Archived posts
    // ----------------------
    public function archived(Request $request)
    {
        // Logged-in student
        $studentId = $request->session()->get('student_id');
        $loggedInStudent = $this->student($studentId); // full model

        // Fetch archived posts for the logged-in student
        $posts = Post::onlyTrashed()
            ->with(['author', 'category', 'comments.author', 'likes'])
            ->withCount(['likes', 'comments'])
            ->where('student_id', $studentId)
            ->latest('deleted_at')
            ->get();

        $categories = PostCategory::orderBy('category_name')->get();

        return view('page.archived', [
            'posts' => $posts,
            'categories' => $categories,
            'student' => $loggedInStudent,              // profile = logged-in student
            'loggedInStudent' => $loggedInStudent,      // full object for sidebar, etc.
            'loggedInStudentId' => $studentId,          // ID for edit/delete checks
        ]);
    }

    // ----------------------
    // Store post
    // ----------------------
    public function store(Request $request)
    {
        $studentId = $request->session()->get('student_id');

        $request->validate([
            'content' => 'required|string',
            'category_id' => 'nullable|exists:post_categories,category_id',
        ]);

        $post = new Post();
        $post->student_id = $studentId;
        $post->content = $request->content;
        $post->category_id = $request->category_id;
        $post->save();

        return back()->with('success', 'Post created!');
    }

    // ----------------------
    // Delete / archive post
    // ----------------------
    public function destroy(Post $post, Request $request)
    {
        $studentId = $request->session()->get('student_id');

        if ($post->student_id !== $studentId) {
            abort(403);
        }

        $post->delete();
        return back()->with('success', 'Post archived');
    }

    // ----------------------
    // Restore post
    // ----------------------
    public function restore($postId, Request $request)
    {
        $studentId = $request->session()->get('student_id');

        $post = Post::withTrashed()->findOrFail($postId);

        if ($post->student_id !== $studentId) {
            abort(403);
        }

        $post->restore();
        return back()->with('success', 'Post restored');
    }

    // ----------------------
    // Force delete post
    // ----------------------
    public function forceDelete($id, Request $request)
    {
        $studentId = $request->session()->get('student_id');

        $post = Post::withTrashed()
            ->where('post_id', $id)
            ->where('student_id', $studentId)
            ->firstOrFail();

        $post->forceDelete();

        return redirect()->back()->with('success', 'Post permanently deleted.');
    }

    // ----------------------
    // Toggle like
    // ----------------------
    public function toggleLike(Post $post, Request $request)
    {
        $studentId = $request->session()->get('student_id');

        if (!$studentId) {
            return back()->with('error', 'Student not logged in.');
        }

        $existingLike = PostLike::where('post_id', $post->post_id)
            ->where('student_id', $studentId)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            PostLike::create([
                'post_id' => $post->post_id,
                'student_id' => $studentId,
            ]);
        }

        return back();
    }

    // ----------------------
    // AJAX search
    // ----------------------
    public function search(Request $request)
    {
        $query = $request->query('q');

        if (!$query) return response()->json([]);

        $posts = Post::with('author', 'category')
            ->where('content', 'LIKE', "%{$query}%")
            ->take(5)->get();

        $categories = PostCategory::where('category_name', 'LIKE', "%{$query}%")
            ->take(5)->get();

        $students = Student::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->take(5)->get();

        return response()->json([
            'posts' => $posts,
            'categories' => $categories,
            'students' => $students,
        ]);
    }

    // ----------------------
    // Helper: get student by id
    // ----------------------
    private function student($studentId)
    {
        return $studentId ? Student::find($studentId) : null;
    }
}
