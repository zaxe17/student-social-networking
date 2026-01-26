@extends('main')

@section('app')
<main class="bg-[#f4f6f9] w-full h-screen overflow-hidden box-border m-0 p-0">
    @yield('page')
</main>

@include('component.postmodal')
@include('component.reportmodal', ['title' => 'Report this post'])
@include('component.editprofilemodal', ['title' => 'Edit Profile'])
@endsection