@extends('layout.app')

@section('page')
<div class="flex flex-col h-screen bg-[url('/public/img/background.jpg')] bg-black/50 bg-blend-multiply bg-cover bg-center">
    <nav class="shadow-nav sticky top-0 w-full h-18 bg-[#770d08] px-13 flex justify-between items-center">
        <!-- LOGO -->
        <div class="flex items-center gap-4">
            <img src="/img/logo.png" alt="pup_logo" class="w-10 h-10">
            <p class="text-2xl text-white font-bold">ISKOnnect</p>
        </div>
    </nav>

    <div class="container flex justify-center items-center mx-auto h-full p-10">
        <form action="{{ route('students.store') }}" method="POST" class="bg-[#f4f6f9]/60 rounded-3xl w-md h-full p-8 gap-1">
            @csrf
            <div class="flex justify-center items-center flex-col gap-1.5">
                <h1 class="text-center font-medium text-3xl">Registration</h1>
                <div class="grid grid-cols-12 gap-4">

                    <!-- Student ID -->
                    <div class="flex flex-col col-span-12">
                        <label for="student_id">Student ID</label>
                        <input type="text" name="student_id" id="student_id" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none" required>
                    </div>

                    <!-- First Name -->
                    <div class="flex flex-col col-span-6">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none" required>
                    </div>

                    <!-- Last Name -->
                    <div class="flex flex-col col-span-6">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none" required>
                    </div>

                    <!-- Birthday -->
                    <div class="flex flex-col col-span-12">
                        <label for="birthday">Birthday</label>
                        <div class="flex gap-2">
                            <!-- Year -->
                            <select name="birth_year" class="text-sm bg-[#dde0e5] w-1/3 h-8 rounded-sm focus:outline-none" required>
                                <option value="">Year</option>
                                @for($year = date('Y'); $year >= 1900; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>

                            <!-- Month -->
                            <select name="birth_month" class="text-sm bg-[#dde0e5] w-1/3 h-8 rounded-sm focus:outline-none" required>
                                <option value="">Month</option>
                                @for($month = 1; $month <= 12; $month++)
                                    <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}">{{ date('F', mktime(0,0,0,$month,1)) }}</option>
                                @endfor
                            </select>

                            <!-- Day -->
                            <select name="birth_day" class="text-sm bg-[#dde0e5] w-1/3 h-8 rounded-sm focus:outline-none" required>
                                <option value="">Day</option>
                                @for($day = 1; $day <= 31; $day++)
                                    <option value="{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}">{{ $day }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Year Level -->
                    <div class="flex flex-col col-span-4">
                        <label for="year_level">Year Level</label>
                        <select name="year_level" id="year_level" class="text-sm bg-[#dde0e5] w-full h-8 rounded-sm focus:outline-none" required>
                            <option value="">Select Year</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                            <option value="5th Year">5th Year</option>
                        </select>
                    </div>

                    <!-- Course -->
                    <div class="flex flex-col col-span-8">
                        <label for="course">Course</label>
                        <input type="text" name="course" id="course" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none" required>
                    </div>

                    <!-- Password -->
                    <div class="flex flex-col col-span-12">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none" required>
                    </div>

                    <!-- Confirm Password -->
                    <div class="flex flex-col col-span-12">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none" required>
                        <p id="password-error" class="text-red-500 text-sm mt-1 hidden">Passwords do not match</p>
                    </div>

                </div>

                <div class="flex justify-center mt-5">
                    <button type="submit" class="bg-[#770d08] w-30 text-white font-medium rounded-md py-1.5">Sign Up</button>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection
