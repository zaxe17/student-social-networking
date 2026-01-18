@extends('layout.app')

@section('page')
@include('layout.navbar')
<div class="container mx-auto flex items-start px-20 py-10 h-screen gap-15">
    <div class="h-full px-18 overflow-scroll no-scrollbar">
        <div class="py-8 border-b border-b-[#770d08] mb-12">
            <h1 class="text-[#36384e] text-3xl font-bold ">Archived Posts</h1>
        </div>
        @include('component.postcard')
    </div>
</div>
@endsection