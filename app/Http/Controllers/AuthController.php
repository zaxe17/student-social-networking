<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

use function Symfony\Component\Clock\now;

class AuthController extends Controller
{
    public function index()
    {
        return view('page.signupin');
    }

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
                'password_hash' => $validated['password'],
                'course'        => $validated['course'],
                'year_level'    => $validated['year_level'],
                'birthday'      => $birthday,
                'bio'      => null,
                'photo'      => null,
                'linkedin'      => null,
                'facebook'      => null,
                'instagram'      => null,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);

            Log::info('Student successfully registered', [
                'student_id' => $student->student_id
            ]);

            return redirect()->back()->with('success', 'Student registered successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to register student', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return redirect()->back()->with('error', 'Failed to register student. Please try again.');
        }
    }
}
