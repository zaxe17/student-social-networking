@extends('layout.app')

@section('page')
@include('layout.navbar')

<div class="container mx-auto px-20 py-10">
    <div class="w-full">

        <!-- USER PROFILE -->
        <div class="flex justify-between items-center border-b border-b-[#770d08] mb-7 pb-10">
            <div class="flex items-center text-3xl gap-6">
                <!-- PROFILE IMAGE -->
                <img
                    src="{{ $student?->photo ? asset('storage/' . $student->photo) : asset('/img/user.png') }}"
                    class="w-32 h-32 rounded-full object-cover"
                    alt="Profile Photo" />

                <div class="flex flex-col">
                    <!-- NAME + COURSE -->
                    <div class="flex items-center gap-8">
                        <h2>{{ $student?->first_name }} {{ $student?->last_name }}</h2>
                        <span class="text-xl text-[#545454]">
                            {{ $student?->course ?? 'N/A' }} | {{ $student?->year_level ?? 'N/A' }}
                        </span>
                    </div>

                    <!-- BIO -->
                    <span class="text-lg text-start italic text-[#545454]">
                        {{ $student?->bio ?: 'No bio yet.' }}
                    </span>
                </div>
            </div>

            <!-- SOCIAL LINKS -->
            @php
                $ig = $student?->instagram;
                $fb = $student?->facebook;
                $li = $student?->linkedin;

                if ($ig && !preg_match('/^https?:\/\//i', $ig)) $ig = 'https://' . $ig;
                if ($fb && !preg_match('/^https?:\/\//i', $fb)) $fb = 'https://' . $fb;
                if ($li && !preg_match('/^https?:\/\//i', $li)) $li = 'https://' . $li;
            @endphp

            <div class="flex items-center gap-3">
                @if(!empty($ig))
                <a href="{{ $ig }}" target="_blank" rel="noopener"
                    class="icon bg-black hover:scale-110 transition"
                    style="--svg: url('https://api.iconify.design/mdi/instagram.svg'); --size: 34px;">
                </a>
                @endif

                @if(!empty($fb))
                <a href="{{ $fb }}" target="_blank" rel="noopener"
                    class="icon bg-[#0e2391] hover:scale-110 transition"
                    style="--svg: url('https://api.iconify.design/mdi/facebook.svg'); --size: 34px;">
                </a>
                @endif

                @if(!empty($li))
                <a href="{{ $li }}" target="_blank" rel="noopener"
                    class="icon bg-[#0a66c2] hover:scale-110 transition"
                    style="--svg: url('https://api.iconify.design/mdi/linkedin.svg'); --size: 34px;">
                </a>
                @endif
            </div>
        </div>

        {{-- USER POSTS --}}
        @if(!empty($posts) && count($posts))
            @foreach($posts as $post)
                @include('component.postcard', ['post' => $post])
            @endforeach
        @else
            <div class="text-center text-[#545454] py-10">
                <p class="text-lg font-medium">No posts yet.</p>
                <p class="text-sm">Create one from the Feed page.</p>
            </div>
        @endif

    </div>
</div>
@endsection
