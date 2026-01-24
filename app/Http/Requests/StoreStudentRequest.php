<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => [
                'required',
                'string',
                'unique:students,student_id',
                'regex:/^\d{4}-\d{5}-MN-0$/',
            ],

            'first_name'  => 'required|string|max:50',
            'last_name'   => 'required|string|max:50',
            'password'    => 'required|string|min:6|confirmed',
            'course'      => 'required|string|max:10',

            'year_level'  => 'required|in:1st Year,2nd Year,3rd Year,4th Year,5th Year',

            'birth_year'  => 'required|digits:4',
            'birth_month' => 'required|digits:2',
            'birth_day'   => 'required|digits:2',
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Student ID is required',
            'student_id.regex' => 'Student ID must follow the format YYYY-#####-MN-0',
            'student_id.unique'   => 'This Student ID is already registered',
            'first_name.required' => 'First name is required',
            'last_name.required'  => 'Last name is required',
            'password.required'   => 'Password is required',
            'password.min'        => 'Password must be at least 6 characters',
            'password.confirmed'  => 'Passwords do not match',
            'course.required'     => 'Course is required',
            'year_level.required' => 'Year level is required',
            'year_level.in'       => 'Invalid year level selected',
            'birth_year.required' => 'Birth year is required',
            'birth_month.required' => 'Birth month is required',
            'birth_day.required'  => 'Birth day is required',
        ];
    }
}
