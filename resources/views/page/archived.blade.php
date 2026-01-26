@extends('layout.app')

@section('title', 'Archive | ISKOnnect')

@section('page')
@include('layout.navbar')

<div class="container mx-auto flex items-start px-20 py-10 h-screen gap-15">
    <div class="w-full h-full px-18 overflow-scroll no-scrollbar">

        <div class="py-8 border-b border-b-[#770d08] mb-12">
            <h1 class="text-[#36384e] text-3xl font-bold">
                Archived Posts
            </h1>
        </div>

        @forelse ($posts as $post)
        @include('component.postcard', ['post' => $post])
        @empty
        <p class="text-center text-gray-500">
            No archived posts.
        </p>
        @endforelse

    </div>
</div>

@include('layout.sidebar', ['student' => $student ?? null])

@include('component.deleteconfirm', [
'title' => 'Delete account',
'modal_id' => 'deleteaccount',
'route' => route('student.delete')
])

@foreach ($posts as $post)
@include('component.deleteconfirm', [
'title' => 'Delete post',
'modal_id' => 'deletepost' . $post->post_id,
'route' => route('posts.forceDelete', ['id' => $post->post_id])
])
@endforeach

@endsection