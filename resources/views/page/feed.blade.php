@extends('layout.app')

@section('title', 'Feed | ISKOnnect')

@section('page')
@include('layout.navbar')

<div class="container mx-auto flex items-start px-20 py-10 h-screen gap-15">

    {{-- LEFT: POSTS --}}
    <div class="shadow-bg w-full h-full px-18 py-13 overflow-scroll no-scrollbar">

        {{-- POST BUTTON / CATEGORY HEADER --}}
        @if(empty($selectedCategories))
        @include('component.postbutton')
        @endif

        @if(!empty($selectedCategories))
        @include('component.categoryname')
        @endif

        {{-- POSTS --}}
        @forelse($posts as $post)
        @include('component.postcard', ['post' => $post])
        @empty
        <p class="text-[#545454] text-center mt-10">No posts yet.</p>
        @endforelse

        {{-- PAGINATION --}}
        @if(method_exists($posts, 'links'))
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
        @endif

    </div>

    {{-- RIGHT: CATEGORIES + EVENTS --}}
    <div class="h-full w-1/3 pb-13 overflow-y-scroll no-scrollbar flex flex-col gap-2.5">
        {{-- CATEGORY FILTER --}}
        @include('component.categoryfilter', [
        'categories' => $categories,
        'categoryId' => $categoryId ?? null
        ])

        {{-- EVENTS WIDGET --}}
        @if(empty($selectedCategories))
        @include('component.eventwidget', ['events' => $sidebarEvents ?? collect()])
        @endif
    </div>
</div>

{{-- MODALS --}}
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

@include('component.deletecomment')

@include('component.createpostmodal', [
'student' => $student,
'categories' => $categories
])

@include('component.createeventmodal')

@include('component.editpostmodal')

@include('layout.sidebar', ['loggedInStudent' => $loggedInStudent])
@endsection