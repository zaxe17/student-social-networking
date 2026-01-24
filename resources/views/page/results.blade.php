@extends('layout.app')

@section('page')
@include('layout.navbar')

<div class="container mx-auto px-20 py-10">
    <h2 class="text-xl mb-5">Search results for "{{ $query }}"</h2>

    @if($posts->count())
        @foreach($posts as $post)
            @include('component.postcard', ['post' => $post])
        @endforeach
    @else
        <p>No posts found for "{{ $query }}"</p>
    @endif
</div>
@include('component.editprofilemodal', ['title' => 'Edit Profile'])
@include('component.changepassmodal', ['title' => 'Change password'])
@endsection