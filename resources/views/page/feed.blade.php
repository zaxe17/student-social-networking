@extends('layout.app')

@section('page')
<div class="grid grid-cols-12 px-20 py-13 h-full gap-15">
    <!-- POST -->
    <div class="shadow-bg col-span-9 px-18 py-13 overflow-scroll no-scrollbar">
        @include('component.createpost')
        @include('component.postcard')
    </div>

    <!-- CATEGORY -->
    <div class="col-span-3">
        @include('component.categoryfilter')
    </div>
</div>
@endSection