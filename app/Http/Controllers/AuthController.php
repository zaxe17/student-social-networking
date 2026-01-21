<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Show signup/login page
    public function index()
    {
        // Redirect to feed if already logged in
        if (session()->has('student_id')) {
            return redirect()->route('feed.page');
        }
        return view('page.signupin');
    }

    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validated();

        try {
            $birthday = "{$validated['birth_year']}-{$validated['birth_month']}-{$validated['birth_day']}";

            $student = Student::create([
                'student_id'    => $validated['student_id'],
                'first_name'    => $validated['first_name'],
                'last_name'     => $validated['last_name'],
                'password_hash' => Hash::make($validated['password']),
                'course'        => $validated['course'],
                'year_level'    => $validated['year_level'],
                'birthday'      => $birthday,
            ]);

            // Store in session
            $request->session()->put('student_id', $student->student_id);

            return redirect()->route('feed.page');
        } catch (\Exception $e) {
            Log::error('Failed to register student', ['error' => $e->getMessage()]);
            return back()->withErrors(['registration' => 'Failed to register student.'])->withInput();
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string',
            'password'   => 'required|string',
        ]);

        $student = Student::where('student_id', $request->student_id)->first();

        if (!$student) {
            return back()->withErrors(['login' => 'Student ID not found'])->withInput();
        }

        if (!Hash::check($request->password, $student->password_hash)) {
            return back()->withErrors(['login' => 'Incorrect password'])->withInput();
        }

        // Successful login → store in session
        $request->session()->put('student_id', $student->student_id);

        return redirect()->route('feed.page');
    }

    // Logout student
    public function logout(Request $request)
    {
        $request->session()->forget('student_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.page');
    }
}