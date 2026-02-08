@extends('layout.app')

@section('title', 'Feed | ISKOnnect')

@section('page')
@include('layout.navbar')

<div class="container mx-auto flex items-start lg:px-20 lg:py-10 h-screen lg:gap-15">

    {{-- LEFT: POSTS --}}
    <div class="shadow-feed w-full h-full lg:px-18 lg:py-13 px-5 pt-5 pb-18 overflow-scroll scrollbar-visible">

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
    <div class="h-full w-1/3 pb-13 overflow-y-scroll no-scrollbar lg:flex hidden flex-col gap-2.5">
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