<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function index()
    {
        // If already logged in, go straight to feed
        if (Auth::check()) {
            return redirect()->route('feed.page');
        }

        return view('page.signupin');
    }

    // SIGN UP
    public function store(StoreStudentRequest $request)
    {
        Log::info('Registration request received', $request->all());

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

            Auth::login($student);

            return redirect()->route('feed.page');

        } catch (\Exception $e) {
            Log::error('Failed to register student', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Failed to register student. Please try again.');
        }
    }

    // SIGN IN
    public function login(Request $request)
    {
        $request->validate([
            'student_id' => ['required'],
            'password'   => ['required'],
        ]);

        $student = Student::where('student_id', $request->student_id)->first();

        if (!$student) {
            return back()->with('error', 'Student ID not found.');
        }

        if (!Hash::check($request->password, $student->password_hash)) {
            return back()->with('error', 'Incorrect password.');
        }

        Auth::login($student);

        return redirect()->route('feed.page');
    }

    // LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.page');
    }
}
