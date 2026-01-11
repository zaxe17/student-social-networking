@extends('main')

@section('app')
<main class="h-screen overflow-hidden">
    @include('layout.navbar')
    @yield('page')
</main>
@endSection