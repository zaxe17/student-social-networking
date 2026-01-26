<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\PostReport;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // ----------------------
    // Feed page (home)
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

        if (!$loggedInStudent) {
            return redirect()->route('auth.page')->with('error', 'Please log in first.');
        }

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

        $categories = PostCategory::orderBy('category_name')->get();

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

        $categories = PostCategory::orderBy('category_name')->get();

        $posts = Post::onlyTrashed()
            ->with(['author', 'category', 'comments.author', 'likes'])
            ->withCount(['likes', 'comments'])
            ->where('student_id', $studentId)
            ->latest('deleted_at')
            ->get();

        return view('page.archived', [
            'posts' => $posts,
            'categories' => $categories,
            'student' => $loggedInStudent,
            'loggedInStudent' => $loggedInStudent,
            'loggedInStudentId' => $studentId,
        ]);
    }

    // ----------------------
    // Update profile (photo, bio, social links)
    // ----------------------
    public function update(Request $request)
    {
        $student = $this->student(session('student_id'));

        if (!$student) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'bio'        => 'nullable|string|max:1000',
            'facebook'   => ['nullable', 'url', 'regex:/^(https?:\/\/)?(www\.)?facebook\.com\/[A-Za-z0-9\.]+\/?$/i'],
            'instagram'  => ['nullable', 'url', 'regex:/^(https?:\/\/)?(www\.)?instagram\.com\/[A-Za-z0-9_\.]+\/?$/i'],
            'linkedin'   => ['nullable', 'url', 'regex:/^(https?:\/\/)?(www\.)?linkedin\.com\/in\/[A-Za-z0-9_-]+\/?$/i'],
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }

            $validated['photo'] = $request->file('photo')
                ->store('profile_photos', 'public');
        }

        $student->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully'
        ]);
    }

    // ----------------------
    // Change password
    // ----------------------
    public function changePassword(Request $request)
    {
        $studentId = $request->session()->get('student_id');
        $student = $this->student($studentId);

        if (!$student) {
            return back()->with('error', 'Student session not found.');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $student->password_hash)) {
            return back()->with('error', 'Wrong current password.');
        }

        $student->password_hash = Hash::make($request->new_password);
        $student->save();

        return back()->with('success', 'Password changed successfully!');
    }

    // ----------------------
    // AJAX: validate old password in real-time
    // ----------------------
    public function validateOldPassword(Request $request)
    {
        $studentId = $request->session()->get('student_id');
        $student = $this->student($studentId);

        if (!$student) {
            return response()->json(['valid' => false]);
        }

        $isValid = Hash::check($request->current_password, $student->password_hash);

        return response()->json(['valid' => $isValid]);
    }

    // ----------------------
    // AJAX: fetch comments for a post
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
    // AJAX: store comment
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
    // Report a post
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
            'post_id'      => $post->post_id,
            'reported_by'  => $studentId,
            'reason'       => $request->reason,
            'details'      => $request->details,
        ]);

        return back()->with('success', 'Report submitted.');
    }

    // ----------------------
    // Helper: get student by ID
    // ----------------------
    private function student($studentId)
    {
        return $studentId ? Student::find($studentId) : null;
    }

    // ----------------------
    // Delete account permanently
    // ----------------------
    public function deleteAccount(Request $request)
    {
        $studentId = $request->session()->get('student_id');
        $student = $this->student($studentId);

        if (!$student) {
            return redirect()->route('auth.page')->with('error', 'Student session not found.');
        }

        // Delete profile photo if exists
        if ($student->photo && Storage::disk('public')->exists($student->photo)) {
            Storage::disk('public')->delete($student->photo);
        }

        // Optionally: delete related posts, comments, likes
        $student->posts()->delete();
        $student->comments()->delete();
        $student->likes()->delete();

        // Delete the student account
        $student->delete();

        // Clear session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.page')->with('success', 'Your account has been permanently deleted.');
    }
}
