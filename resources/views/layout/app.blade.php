@extends('main')

@section('app')
<main class="bg-[#f4f6f9] w-full h-screen overflow-hidden box-border m-0 p-0">
    @yield('page')

    @auth
        @include('layout.sidebar')
    @endauth
</main>

@auth
    @include('component.createpostmodal', ['title' => 'Create new post'])
    @include('component.postmodal', ['title' => 'Jan Marc\'s post'])
    @include('component.reportmodal', ['title' => 'Report this post'])
    @include('component.changepassmodal', ['title' => 'Change password'])
    @include('component.editprofilemodal', ['title' => 'Edit Profile'])
@endauth
@endsection
