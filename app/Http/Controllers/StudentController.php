<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|max:15|unique:students,student_id',
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'password'   => 'required|string|min:6',
            'course'     => 'required|string|max:10',
            'year_level' => 'required|in:1st Year,2nd Year,3rd Year,4th Year,5th Year',
            'birthday'   => 'required|date',
        ]);

        Student::create([
            'student_id' => $validated['student_id'],
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'password_hash' => $validated['password'], // automatically hashed in model
            'course'     => $validated['course'],
            'year_level' => $validated['year_level'],
            'birthday'   => $validated['birthday'],
        ]);

        return redirect()->back()->with('success', 'Student registered successfully!');
    }
}
