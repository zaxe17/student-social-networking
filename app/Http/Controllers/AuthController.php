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
        // Redirect to feed if already logged in
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
            /**
             * Birthday handling
             * - Uses Carbon (Luis)
             * - Still compatible with simple string usage (Aro)
             */
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

            // Store login session
            $request->session()->put('student_id', $student->student_id);

            // Support AJAX + normal form submit
            if ($request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'redirect' => route('feed.page')
                ]);
            }

            return redirect()->route('feed.page');
        } catch (\Exception $e) {
            Log::error('Signup failed', ['error' => $e->getMessage()]);

            if ($request->ajax()) {
                return response()->json([
                    'errors' => [
                        'registration' => ['Failed to register student. Student ID may already exist.']
                    ]
                ], 500);
            }

            return back()
                ->withErrors(['registration' => 'Failed to register student.'])
                ->withInput();
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

        /**
         * Combined login logic:
         * - Aro: specific error messages
         * - Luis: single generic error
         */
        if (!$student) {
            return back()->withErrors([
                'login' => 'Student ID not found'
            ])->withInput();
        }

        if (!Hash::check($request->password, $student->password_hash)) {
            return back()->withErrors([
                'login' => 'Incorrect password'
            ])->withInput();
        }

        // Successful login
        $request->session()->put('student_id', $student->student_id);

        return redirect()->route('feed.page');
    }

    // LOGOUT
    public function logout(Request $request)
    {
        // Only remove student session, keep admin session intact
        $request->session()->forget('student_id');
        // Optional: regenerate CSRF token to stay safe
        $request->session()->put('_token', csrf_token());

        return redirect()->route('auth.page');
    }
}