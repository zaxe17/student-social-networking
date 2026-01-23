@extends('layout.app')

@section('page')
@include('layout.navbar')
<div class="container mx-auto flex items-start px-20 py-10 h-screen gap-15">
    <!-- POST -->
    <div class="shadow-bg w-full h-full px-18 py-13 overflow-scroll no-scrollbar">
        @include('component.postbutton')

        {{-- Loop through all posts --}}
        @foreach($posts as $post)
        @include('component.postcard', ['post' => $post])
        @endforeach
    </div>

    <!-- CATEGORY -->
    <div class="h-full">
        @include('component.categoryfilter')
    </div>
</div>

@include('component.createpostmodal', [
    'student' => $student,
    'categories' => $categories,
    'post' => $posts->first() ?? null, // safely pass first post or null
])


@include('layout.sidebar', ['student' => $student ?? null])
@endsection