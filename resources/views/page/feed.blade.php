@extends('layout.app')

@section('page')
@include('layout.navbar')
<div class="container mx-auto flex items-start px-20 py-10 h-screen gap-15">
    <!-- POST -->
    <div class="shadow-bg h-full px-18 py-13 overflow-scroll no-scrollbar">
        @include('component.createpost')
        @include('component.postcard')
    </div>

    <!-- CATEGORY -->
    <div class="h-full">
        @include('component.categoryfilter')
    </div>
</div>

@include('component.postmodal', [
'title' => 'Create new post'

])
@endSection