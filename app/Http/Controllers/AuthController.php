<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AuthController extends Controller
{
    // Show signup/login page
    public function index()
    {
        if (session()->has('student_id')) {
            return redirect()->route('feed.page');
        }

        return view('page.signupin');
    }

    // SIGN UP
    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validated();

        try {
            $birthday = Carbon::createFromDate(
                $validated['birth_year'],
                $validated['birth_month'],
                $validated['birth_day']
            )->toDateString();

            $student = Student::create([
                'student_id'    => $validated['student_id'],
                'first_name'    => $validated['first_name'],
                'last_name'     => $validated['last_name'],
                'password_hash' => Hash::make($validated['password']),
                'course'        => $validated['course'],
                'year_level'    => $validated['year_level'],
                'birthday'      => $birthday,
            ]);

            session()->put('student_id', $student->student_id);

            return response()->json([
                'success'  => true,
                'redirect' => route('feed.page')
            ]);

        } catch (\Exception $e) {
            Log::error('Signup failed', ['error' => $e->getMessage()]);

            return response()->json([
                'errors' => [
                    'registration' => ['Failed to register student. Student ID may already exist.']
                ]
            ], 500);
        }
    }

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string',
            'password'   => 'required|string',
        ]);

        $student = Student::where('student_id', $request->student_id)->first();

        if (!$student || !Hash::check($request->password, $student->password_hash)) {
            return back()->withErrors([
                'login' => 'Invalid Student ID or password'
            ]);
        }

        session()->put('student_id', $student->student_id);

        return redirect()->route('feed.page');
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerateToken();

        return redirect()->route('auth.page');
    }
}