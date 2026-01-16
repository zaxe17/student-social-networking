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
            'student_id'   => 'required|string|max:15|unique:students,student_id',
            'first_name'   => 'required|string|max:50',
            'last_name'    => 'required|string|max:50',
            'password'     => 'required|string|min:6|confirmed',
            'course'       => 'required|string|max:10',
            'year_level'   => 'required|in:1st Year,2nd Year,3rd Year,4th Year,5th Year',
            'birth_year'   => 'required|digits:4',
            'birth_month'  => 'required|digits:2',
            'birth_day'    => 'required|digits:2',
        ];
    }

    public function messages(): array
    {
        return [
            'password'     => 'password must 6 character',
            'course'       => '',
            'year_level'   => '',
            'birth_year'   => '',
            'birth_month'  => '',
            'birth_day'    => '',
        ];
    }
}
