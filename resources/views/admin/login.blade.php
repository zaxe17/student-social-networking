@extends('layout.app')

@section('page')
<div class="min-h-screen bg-gray-100">
    <nav class="bg-[#770d08] px-10 py-4">
        <div class="flex items-center gap-4">
            <img src="/img/logo.png" class="w-10 h-10">
            <p class="text-2xl text-white font-bold">ISKOnnect</p>
        </div>
    </nav>

    <div class="flex justify-center items-center py-20">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-lg p-10">
            <h1 class="text-3xl font-semibold text-center mb-6">Admin Login</h1>

            @if ($errors->has('login'))
                <p class="text-red-600 text-sm text-center mb-4">
                    {{ $errors->first('login') }}
                </p>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf

                <input
                    name="username"
                    placeholder="Username"
                    class="w-full bg-gray-100 rounded-md px-3 py-2"
                    required
                >

                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    class="w-full bg-gray-100 rounded-md px-3 py-2"
                    required
                >

                <button class="w-full bg-[#770d08] text-white py-2 rounded-md">
                    Sign In
                </button>
            </form>

            <p class="text-xs text-center mt-4 text-gray-500">
                Access via <b>/adminn</b>
            </p>
        </div>
    </div>
</div>
@endsection
