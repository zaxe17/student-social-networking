@extends('layout.app')

@section('title', $student->first_name . ' ' . $student->last_name . "'s Profile | ISKOnnect")
@section('page')
@include('layout.navbar')
@include('layout.sidebar', ['loggedInStudent' => $loggedInStudent])
@include('component.changepassmodal', ['title' => 'Change password'])

<div class="container mx-auto flex items-start px-20 py-10 h-screen gap-15">
    <!-- USER PROFILE -->
    <div class="w-full h-full px-18 pb-13 overflow-scroll no-scrollbar">
        <div class="flex justify-between items-center border-b border-b-[#770d08] mb-7 pb-10">
            <div class="flex items-center text-3xl gap-6">

                <img
                    src="{{ $student->photo ? asset('storage/'.$student->photo) : asset('/img/user.png') }}"
                    class="w-40 h-40 rounded-full object-cover border"
                    alt="Profile Picture">

                <div class="flex flex-col">
                    <div class="flex items-center gap-8">
                        <h2>{{ $student->first_name }} {{ $student->last_name }}</h2>
                        <span>{{ $student->year_level }} | {{ $student->course }}</span>
                    </div>
                    <span class="text-lg text-start italic">{{ $student->bio ?? '' }}</span>
                </div>
            </div>

            <!-- SOCIAL LINKS -->
            <div class="flex items-center gap-2">
                @if($student->instagram)
                <a href="{{ $student->instagram }}" target="_blank">
                    <span class="icon bg-black" style="--svg: url('https://api.iconify.design/mdi/instagram.svg'); --size: 30px;"></span>
                </a>
                @endif

                @if($student->facebook)
                <a href="{{ $student->facebook }}" target="_blank">
                    <span class="icon bg-[#0e2391]" style="--svg: url('https://api.iconify.design/mdi/facebook.svg'); --size: 30px;"></span>
                </a>
                @endif

                @if($student->linkedin)
                <a href="{{ $student->linkedin }}" target="_blank">
                    <span class="icon bg-[#0a66c2]" style="--svg: url('https://api.iconify.design/mdi/linkedin.svg'); --size: 30px;"></span>
                </a>
                @endif
            </div>
        </div>

        <!-- POSTS -->
        @foreach($posts as $post)
        @include('component.postcard', [
            'post' => $post,
            'student' => $student,
            'categories' => $categories
        ])
        @endforeach
    </div>
</div>
@include('component.editprofilemodal', ['title' => 'Edit Profile'])
@include('component.deleteaccountmodal')
@endsection
