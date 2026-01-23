<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Show profile page
    public function index(Request $request)
    {
        $studentId = $request->session()->get('student_id');
        $loggedInStudent = $this->student($studentId);

        if (!$loggedInStudent) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        $posts = Post::with(['author', 'category', 'comments.author', 'likes'])
            ->withCount(['likes', 'comments'])
            ->where('student_id', $studentId)
            ->latest('created_at')
            ->get();

        $categories = PostCategory::orderBy('category_name')->get();

        return view('page.profile', [
            'student' => $loggedInStudent,
            'posts' => $posts,
            'categories' => $categories,
            'loggedInStudent' => $loggedInStudent,
            'loggedInStudentId' => $loggedInStudent->student_id,
        ]);
    }

    // Update profile

    public function update(Request $request)
    {
        $student = Student::find(session('student_id'));

        if (!$student) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'bio'        => 'nullable|string|max:1000',
            'facebook'   => 'nullable|url',
            'instagram'  => 'nullable|url',
            'linkedin'   => 'nullable|url',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($student->photo && \Storage::disk('public')->exists($student->photo)) {
                \Storage::disk('public')->delete($student->photo);
            }

            $validated['photo'] = $request->file('photo')
                ->store('profile_photos', 'public');
        }

        $student->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully'
        ]);
    }

    public function changePassword(Request $request)
    {
        $studentId = $request->session()->get('student_id');
        $student = $studentId ? Student::find($studentId) : null;

        if (!$student) {
            return back()->with('error', 'Student session not found.');
        }

        // Validate the inputs
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed', // 'confirmed' checks new_password_confirmation
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $student->password_hash)) {
            return back()->with('error', 'Wrong current password.');
        }

        // Update the password
        $student->password_hash = Hash::make($request->new_password);
        $student->save();

        return back()->with('success', 'Password changed successfully!');
    }

    private function student($studentId)
    {
        $studentModel = new Student();
        $student = $studentId ? $studentModel->find($studentId) : null;

        return $student;
    }
}
