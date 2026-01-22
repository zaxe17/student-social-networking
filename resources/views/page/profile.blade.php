@extends('layout.app')

@section('page')
@include('layout.navbar')

<div class="container mx-auto flex items-start px-20 py-10 h-screen gap-15">
    <!-- USER PROFILE -->
    <div class="w-full h-full px-18 pb-13 overflow-scroll no-scrollbar">
        <div class="flex justify-between items-center border-b border-b-[#770d08] mb-7 pb-10">
            <div class="flex items-center text-3xl gap-6">
                <img src="/img/user.png" alt="" class="w-27 h-27 rounded-full object-cover cursor-pointer border-3 border-gray-300">
                <div class="flex flex-col">
                    <div class="flex items-center gap-8">
                        <h2>{{ $student->first_name }} {{ $student->last_name }}</h2>
                        <span>{{ $student->year_level }} | {{ $student->course }}</span>
                    </div>
                    <span class="text-lg text-start italic">{{ $student->bio ?? '' }}</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ $student->instagram }}" class="{{ $student->instagram === null ? 'hidden' : '' }}">
                    <span class="icon bg-black" style="--svg: url('https://api.iconify.design/mdi/instagram.svg'); --size: 30px;"></span>
                </a>
                <a href="{{ $student->facebook }}" class="{{ $student->facebook === null ? 'hidden' : '' }}">
                    <span class="icon bg-[#0e2391]" style="--svg: url('https://api.iconify.design/mdi/facebook.svg'); --size: 30px;"></span>
                </a>
                <a href="{{ $student->linkedin }}" class="{{ $student->linkedin === null ? 'hidden' : '' }}">
                    <span class="icon bg-[#0a66c2]" style="--svg: url('https://api.iconify.design/mdi/linkedin.svg'); --size: 30px;"></span>
                </a>
            </div>
        </div>

        @foreach($posts as $post)
        @include('component.postcard', [
        'post' => $post,
        'student' => $student,
        'categories' => $categories
        ])
        @endforeach
    </div>
</div>

@include('layout.sidebar', ['student' => $student ?? null])
@endsection