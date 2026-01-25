@extends('layout.app')

@section('title', 'Sign Up or Sign In | ISKOnnect')

@section('page')
<div class="flex flex-col h-screen bg-[url('/public/img/background.jpg')] bg-black/60 bg-blend-multiply bg-cover bg-center">
    <nav class="shadow-nav sticky top-0 w-full h-18 bg-[#770d08] px-13 flex justify-between items-center">
        <!-- LOGO -->
        <div class="flex items-center gap-4">
            <img src="/img/logo.png" alt="pup_logo" class="w-10 h-10">
            <p class="text-2xl text-white font-bold">ISKOnnect</p>
        </div>
    </nav>

    <div class="mx-auto w-full h-full">
        <!-- SIGNIN FORM -->
        <div id="signin-form" class="flex justify-between w-full h-full">
            <div class="w-1/2 h-full flex flex-col justify-end items-start">
                <div class="mx-25 my-20">
                    <p class="text-6xl text-center text-white">Welcome back,</p>
                    <p class="text-6xl text-center text-white">fellow <span class="font-bold">PUPian!</span></p>
                </div>
            </div>

            <form action="{{ route('students.login') }}" method="POST" class="shadow-form bg-[#f4f6f9]/40 w-1/2 px-35 backdrop-blur-sm">
                @csrf
                <div class="w-full h-full flex flex-col justify-center gap-8">
                    <h2 class="text-4xl font-medium">Sign-in</h2>

                    @if ($errors->has('login'))
                    <p class="text-red-500 text-sm mt-1">{{ $errors->first('login') }}</p>
                    @endif

                    <div class="grid grid-cols-12 gap-2">
                        <div class="flex flex-col col-span-12">
                            <label for="student_id">Student ID</label>
                            <input type="text" name="student_id" id="student_id" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none" required>
                        </div>
                        <div class="flex flex-col col-span-12">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none" required>
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <button
                            type="submit"
                            class="bg-[#770d08] w-30 text-white font-medium rounded-md py-1.5 cursor-pointer">
                            Sign In
                        </button>
                    </div>

                    <p class="text-center">Don’t have an account yet? <span id="show-signup" class="font-medium underline cursor-pointer">Sign-up</span></p>
                </div>
            </form>
        </div>

        <!-- SIGNUP FORM -->
        <div id="signup-form" class="hidden flex justify-center items-center p-4 lg:p-10 h-full">
            <form action="{{ route('students.store') }}" method="POST" class="shadow-form bg-[#f4f6f9]/25 rounded-3xl w-md p-8 backdrop-blur-sm">
                @csrf
                <div class="flex flex-col justify-center items-center gap-1.5">

                    <h1 class="text-center font-medium text-3xl">Registration</h1>

                    <div class="grid grid-cols-12 gap-2">
                        <!-- Student ID -->
                        <div class="flex flex-col col-span-12">
                            <label for="student_id">Student ID</label>
                            <input type="text" name="student_id" id="student_id" value="{{ old('student_id') }}" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none">
                            @error('student_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- First Name -->
                        <div class="flex flex-col col-span-6">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none" required>
                            @error('first_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Last Name -->
                        <div class="flex flex-col col-span-6">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none" required>
                            @error('last_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Birthday -->
                        <div class="flex flex-col col-span-12">
                            <label for="birthday">Birthday</label>
                            <div class="flex gap-2">
                                <select name="birth_year" class="text-sm bg-[#dde0e5] w-1/3 h-8 rounded-sm focus:outline-none" required>
                                    <option value="">Year</option>
                                    @for($year = date('Y'); $year >= 1900; $year--)
                                    <option value="{{ $year }}" {{ old('birth_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>

                                <select name="birth_month" class="text-sm bg-[#dde0e5] w-1/3 h-8 rounded-sm focus:outline-none" required>
                                    <option value="">Month</option>
                                    @for($month = 1; $month <= 12; $month++)
                                        <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}" {{ old('birth_month') == str_pad($month, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$month,1)) }}</option>
                                        @endfor
                                </select>

                                <select name="birth_day" class="text-sm bg-[#dde0e5] w-1/3 h-8 rounded-sm focus:outline-none" required>
                                    <option value="">Day</option>
                                    @for($day = 1; $day <= 31; $day++)
                                        <option value="{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}" {{ old('birth_day') == str_pad($day, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ $day }}</option>
                                        @endfor
                                </select>
                            </div>
                            @error('birth_year')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            @error('birth_month')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            @error('birth_day')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Year Level -->
                        <div class="flex flex-col col-span-4">
                            <label for="year_level">Year Level</label>
                            <select name="year_level" id="year_level" class="text-sm bg-[#dde0e5] w-full h-8 rounded-sm focus:outline-none" required>
                                <option value="">Select Year</option>
                                @foreach(['1st Year','2nd Year','3rd Year','4th Year','5th Year'] as $year)
                                <option value="{{ $year }}" {{ old('year_level') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                            @error('year_level')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Course -->
                        <div class="flex flex-col col-span-8">
                            <label for="course">Course</label>
                            <input type="text" name="course" id="course" value="{{ old('course') }}" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none" required>
                            @error('course')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Password -->
                        <div class="flex flex-col col-span-12">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none" required>

                            @if ($errors->has('registration'))
                            <p class="text-red-500 text-sm mt-1">{{ $errors->first('registration') }}</p>
                            @endif

                            @error('student_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            @error('first_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            @error('last_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            @error('password_confirmation')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            @error('course')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            @error('year_level')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            @error('birth_year')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            @error('birth_month')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            @error('birth_day')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="flex flex-col col-span-12">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none" required>
                            <p id="password-error" class="text-red-500 text-sm mt-1 hidden">Passwords do not match</p>
                        </div>
                    </div>

                    <div class="flex justify-center mt-5">
                        <button
                            type="submit"
                            class="bg-[#770d08] w-30 text-white font-medium rounded-md py-1.5 cursor-pointer">
                            Sign Up
                        </button>
                    </div>

                    <p id="show-signin" class="text-center">Have an account already? <span class="font-medium underline cursor-pointer">Sign-in</span></p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ===== TOGGLE FORMS =====
        const signinForm = document.getElementById('signin-form');
        const signupForm = document.getElementById('signup-form');
        const showSignup = document.getElementById('show-signup');
        const showSignin = document.getElementById('show-signin');

        showSignup?.addEventListener('click', () => {
            signinForm.classList.add('hidden');
            signupForm.classList.remove('hidden');
        });

        showSignin?.addEventListener('click', () => {
            signupForm.classList.add('hidden');
            signinForm.classList.remove('hidden');
        });

        // ===== SIGNUP VALIDATION & AJAX =====
        const form = document.querySelector('#signup-form form');
        if (!form) return;

        const password = form.querySelector('input[name="password"]');
        const confirmPassword = form.querySelector('input[name="password_confirmation"]');
        const passwordError = document.getElementById('password-error');
        const requiredInputs = form.querySelectorAll('input, select');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            document.querySelectorAll('.error-msg').forEach(el => el.remove());

            let valid = true;

            requiredInputs.forEach(input => {
                if (!input.value) {
                    input.classList.add('border-red-500', 'border');
                    valid = false;
                } else {
                    input.classList.remove('border-red-500', 'border');
                }
            });

            if (password.value !== confirmPassword.value) {
                passwordError.classList.remove('hidden');
                valid = false;
            } else {
                passwordError.classList.add('hidden');
            }

            if (!valid) return;

            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const contentType = response.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) return;

                const data = await response.json();

                if (data.errors) {
                    Object.entries(data.errors).forEach(([field, messages]) => {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (input) {
                            const p = document.createElement('p');
                            p.className = 'text-red-500 text-sm mt-1 error-msg';
                            p.textContent = messages[0];
                            input.insertAdjacentElement('afterend', p);
                        }
                    });
                }

                if (data.success && data.redirect) window.location.href = data.redirect;

            } catch (err) {
                console.error('Signup failed', err);
            }
        });
    });
</script>
@endsection