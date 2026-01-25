@extends('layout.app')

@section('title', 'Feed | ISKOnnect')

@section('page')
@include('layout.navbar')
@include('component.changepassmodal', ['title' => 'Change password'])

<div class="w-full px-10 py-8">
    <div class="max-w-7xl mx-auto grid grid-cols-12 gap-8 items-start">

        {{-- LEFT: POSTS --}}
        <div class="col-span-12 lg:col-span-8">
            <div class="shadow-bg w-full h-[calc(100vh-8rem)] px-8 py-6 overflow-y-auto no-scrollbar rounded-lg">

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
        </div>

        {{-- RIGHT: CATEGORIES + EVENTS --}}
        <div class="col-span-12 lg:col-span-4">
            <div class="w-full h-[calc(100vh-8rem)] overflow-y-auto no-scrollbar sticky top-[6.5rem] space-y-6">
                
                {{-- CATEGORY FILTER --}}
                @include('component.categoryfilter', [
                    'categories' => $categories,
                    'categoryId' => $categoryId ?? null
                ])

                {{-- EVENTS WIDGET --}}
                @include('component.eventwidget', ['events' => $sidebarEvents ?? collect()])

            </div>
        </div>

    </div>
</div>

{{-- MODALS --}}
@include('component.deleteaccountmodal')
@include('component.createpostmodal', [
    'student' => $student,
    'categories' => $categories
])
@include('component.createeventmodal')

@include('layout.sidebar', ['loggedInStudent' => $loggedInStudent])
@endsection
