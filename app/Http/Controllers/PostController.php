<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostLike;
use App\Models\Student;
use App\Models\PostComment;
use App\Models\PostReport;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    // ----------------------
    // Feed page
    // ----------------------
    public function index(Request $request)
    {
        $studentId = $request->session()->get('student_id');
        $loggedInStudent = $this->student($studentId);

        if (!$loggedInStudent) {
            return redirect()->route('auth.page')->with('error', 'Please log in first.');
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
        $studentId = $request->session()->get('student_id');
        $loggedInStudent = $this->student($studentId);

        $categories = PostCategory::orderBy('category_name')->get();

        $posts = Post::with(['author', 'category', 'comments.author', 'likes'])
            ->withCount(['likes', 'comments'])
            ->where('student_id', $studentId)
            ->latest('created_at')
            ->get();

        return view('page.profile', [
            'student' => $loggedInStudent,
            'posts' => $posts,
            'categories' => $categories,
            'loggedInStudent' => $loggedInStudent,
            'loggedInStudentId' => $studentId,
        ]);
    }

    // ----------------------
    // View another student's profile
    // ----------------------
    public function viewProfile($student_id, Request $request)
    {
        $student = Student::where('student_id', $student_id)->firstOrFail();

        $loggedInStudentId = $request->session()->get('student_id');
        $loggedInStudent = $this->student($loggedInStudentId);

        $posts = $student->posts()->with(['author', 'category', 'comments.author', 'likes'])
            ->withCount(['likes', 'comments'])
            ->latest('created_at')
            ->get();

        $categories = PostCategory::all();

        return view('page.profile', [
            'student' => $student,
            'posts' => $posts,
            'categories' => $categories,
            'loggedInStudent' => $loggedInStudent,
            'loggedInStudentId' => $loggedInStudentId,
        ]);
    }

    // ----------------------
    // Archived posts
    // ----------------------
    public function archived(Request $request)
    {
        $studentId = $request->session()->get('student_id');
        $loggedInStudent = $this->student($studentId);

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
            'student' => $loggedInStudent,
            'loggedInStudent' => $loggedInStudent,
            'loggedInStudentId' => $studentId,
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
    // Edit post (AJAX)
    // ----------------------
    public function edit(Request $request, Post $post)
    {
        $studentId = $request->session()->get('student_id');

        if (!$studentId || $post->student_id !== $studentId) {
            abort(403);
        }

        $request->validate(['content' => 'required|string']);

        $post->content = $request->content;
        $post->save();

        return response()->json(['success' => true]);
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

        Log::info("Post id: " . $post->post_id);
        Log::info("Student id: " . $post->student_id);
        Log::info("Post content: " . $post->content);

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
            return response()->json(['error' => 'Not logged in'], 403);
        }

        $existingLike = PostLike::where('post_id', $post->post_id)
            ->where('student_id', $studentId)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            PostLike::create([
                'post_id' => $post->post_id,
                'student_id' => $studentId,
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes()->count(),
        ]);
    }

    // ----------------------
    // Category filter
    // ----------------------
    public function category(Request $request)
    {
        $studentId = $request->session()->get('student_id');
        $student = $this->student($studentId);

        $categories = PostCategory::orderBy('category_name')->get();
        $selectedCategories = $request->input('category', []);

        if (empty($selectedCategories)) {
            return redirect()->route('feed.page');
        }

        $posts = Post::with(['author', 'category', 'comments.author', 'likes'])
            ->withCount(['likes', 'comments'])
            ->whereIn('category_id', $selectedCategories)
            ->latest('created_at')
            ->get();

        return view('page.feed', [
            'posts' => $posts,
            'categories' => $categories,
            'student' => $student,
            'selectedCategories' => $selectedCategories,
            'loggedInStudent' => $student,
        ]);
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
    // Fetch comments (AJAX)
    // ----------------------
    public function fetchComments(Post $post)
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

    // ----------------------
    // Store comment (AJAX)
    // ----------------------
    public function storeComment(Request $request, Post $post)
    {
        $studentId = $request->session()->get('student_id');

        if (!$studentId) return response()->json(['error' => 'Not logged in'], 403);

        $request->validate(['content' => 'required|string']);

        $comment = PostComment::create([
            'post_id' => $post->post_id,
            'student_id' => $studentId,
            'content' => $request->content,
        ]);

        $comment->load('author');

        $commentHtml = view('component.comment', ['comment' => $comment])->render();
        $commentsCount = $post->comments()->count();

        return response()->json([
            'success' => true,
            'comment_html' => $commentHtml,
            'comments_count' => $commentsCount,
        ]);
    }

    // ----------------------
    // Report post
    // ----------------------
    public function reportPost(Request $request, Post $post)
{
    $studentId = $request->session()->get('student_id');

    if (!$studentId) {
        return back()->with('error', 'You must be logged in.');
    }

    $request->validate([
        'reason'  => 'required|string|max:255',
        'details' => 'nullable|string|max:2000',
    ]);

    PostReport::create([
        'post_id'     => $post->post_id,
        'reported_by' => $studentId,
        'reason'      => $request->reason,
        'details'     => $request->details,
    ]);

    return back()->with('success', 'Report submitted.');
}

    // ----------------------
    // Helper: get student by id
    // ----------------------
    private function student($studentId)
    {
        return $studentId ? Student::find($studentId) : null;
    }

    public function reactors(Post $post)
    {
        $users = $post->likesWithUser()
            ->with('student')
            ->get()
            ->pluck('student')
            ->map(fn($s) => [
                'name' => $s->first_name . ' ' . $s->last_name,
                'photo' => $s->photo
                    ? asset('storage/' . $s->photo)
                    : asset('/img/user.png'),
            ]);

        return response()->json($users);
    }
}
