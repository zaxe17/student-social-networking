@extends('layout.app')

@section('page')
@include('layout.navbar')

<div class="container mx-auto flex items-start px-20 py-10 h-screen gap-15">
    <!-- POST -->
    <div class="shadow-bg w-full h-full px-18 py-13 overflow-scroll no-scrollbar">
        @include('component.categoryname')

        @foreach($posts as $post)
        @include('component.postcard', ['post' => $post])
        @endforeach

    </div>

    <!-- CATEGORY -->
    <div class="h-full">
        @include('component.categoryfilter')
    </div>
</div>

@include('layout.sidebar', ['student' => $student ?? null])
@include('component.changepassmodal', ['title' => 'Change password'])
@include('component.deleteconfirm', [
'title' => 'Delete account',
'modal_id' => 'deleteaccount',
'route' => route('student.delete')
])
@include('component.editpostmodal')
@endsection