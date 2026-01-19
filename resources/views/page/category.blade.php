@extends('layout.app')

@section('page')
@include('layout.navbar')

<div class="w-full px-10 py-8">
    <div class="max-w-7xl mx-auto grid grid-cols-12 gap-8 items-start">

        {{-- LEFT: POSTS --}}
        <div class="col-span-12 lg:col-span-8">
            <div class="shadow-bg w-full h-[calc(100vh-8rem)] px-8 py-6 overflow-y-auto no-scrollbar rounded-lg">
                @include('component.postbutton')

                @forelse($posts as $post)
                    @include('component.postcard', ['post' => $post])
                @empty
                    <p class="text-[#545454] text-center mt-10">No posts yet.</p>
                @endforelse
            </div>
        </div>

        {{-- RIGHT: CATEGORIES --}}
        <div class="col-span-12 lg:col-span-4">
            <div class="w-full h-[calc(100vh-8rem)] overflow-y-auto no-scrollbar sticky top-[6.5rem]">
                @include('component.categoryfilter', [
                    'categories' => $categories,
                    'categoryId' => $categoryId ?? null
                ])
            </div>
        </div>

    </div>
</div>
@endsection
