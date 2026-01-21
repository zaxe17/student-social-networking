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
    /* public function index(Request $request)
    {
        $studentId = $request->session()->get('student_id');
        $student = $studentId ? Student::find($studentId) : null;

        $posts = $student
            ? Post::with(['author', 'category', 'comments.author'])
            ->withCount(['likes', 'comments'])
            ->where('student_id', $student->student_id)
            ->latest('created_at')
            ->get()
            : [];

        $categories = PostCategory::orderBy('category_name')->get();

        // Pass $student to the app.blade layout
        return view('page.profile', compact('student', 'posts', 'categories'));
    } */



    // Update profile
    public function update(Request $request)
    {
        $studentId = $request->session()->get('student_id');
        $student = Student::find($studentId);

        if (!$student) {
            return back()->with('error', 'Student session not found.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'bio'        => 'nullable|string|max:1000',
            'facebook'   => 'nullable|url',
            'instagram'  => 'nullable|url',
            'linkedin'   => 'nullable|url',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $student->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'bio'        => $request->bio,
            'facebook'   => $request->facebook,
            'instagram'  => $request->instagram,
            'linkedin'   => $request->linkedin,
            'photo'      => $request->hasFile('photo')
                ? $request->file('photo')->store('profile_photos', 'public')
                : $student->photo,
        ]);

        return back()->with('success', 'Profile updated!');
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
