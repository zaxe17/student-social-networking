<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        // Log incoming request for debugging
        Log::info('Registration request received', $request->all());

        $request->validate([
            'student_id' => 'required|string|max:15|unique:students,student_id',
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'password'   => 'required|string|min:6|confirmed', // confirmed checks password_confirmation
            'course'     => 'required|string|max:10',
            'year_level' => 'required|in:1st Year,2nd Year,3rd Year,4th Year,5th Year',
            'birth_year' => 'required|digits:4',
            'birth_month'=> 'required|digits:2',
            'birth_day'  => 'required|digits:2',
        ]);

        $birthday = $request->birth_year . '-' . $request->birth_month . '-' . $request->birth_day;

        try {
            $student = Student::create([
                'student_id'    => $request->student_id,
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'password_hash' => $request->password, // auto hashed
                'course'        => $request->course,
                'year_level'    => $request->year_level,
                'birthday'      => $birthday,
            ]);

            // Log success
            Log::info('Student successfully registered', ['student_id' => $student->student_id]);

            return redirect()->back()->with('success', 'Student registered successfully!');
        } catch (\Exception $e) {
            // Log error if something goes wrong
            Log::error('Failed to register student', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return redirect()->back()->with('error', 'Failed to register student. Please try again.');
        }
    }
}
