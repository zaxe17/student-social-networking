@extends('layout.app')

@section('page')
<div class="flex flex-col h-screen bg-[url('/public/img/background.jpg')] bg-black/50 bg-blend-multiply bg-cover bg-center">
    <nav class="shadow-nav sticky top-0 w-full h-18 bg-[#770d08] px-13 flex justify-between items-center">
        <!-- LOGO -->
        <div class="flex items-center gap-4">
            <img src="/img/logo.png" alt="pup_logo" class="w-10 h-10">
            <p class="text-2xl text-white font-bold">ISKOnnect</p>
        </div>
    </nav>

    <!-- BODY -->
    <div class="container flex justify-center items-center mx-auto h-full p-10">
        <!-- REGITRATION FORM -->
        <form action="" method="" class="bg-[#f4f6f9]/60 flex flex-col justify-between items-center rounded-3xl w-md h-full p-8 gap-1">
            <h1 class="text-center font-medium text-3xl">Registration</h1>
            <div class="grid grid-cols-12 gap-4">
                @include('component.input', [
                'label' => 'Student ID',
                'col_span' => 12
                ])

                @include('component.input', [
                'label' => 'First Name',
                'col_span' => 6
                ])
                @include('component.input', [
                'label' => 'Last Name',
                'col_span' => 6
                ])

                @include('component.input', [
                'label' => 'Birthday',
                'col_span' => 4
                ])
                @include('component.input', [
                'label' => ' ',
                'col_span' => 4
                ])
                @include('component.input', [
                'label' => ' ',
                'col_span' => 4
                ])

                @include('component.input', [
                'label' => 'Year Level',
                'col_span' => 4
                ])
                @include('component.input', [
                'label' => 'Course',
                'col_span' => 8
                ])

                @include('component.input', [
                'label' => 'Year Level',
                'col_span' => 12
                ])
                @include('component.input', [
                'label' => 'Course',
                'col_span' => 12
                ])
            </div>

            <div class="flex justify-center">
                <button type="submit" class="bg-[#770d08] w-30 text-white font-medium rounded-md py-1.5">Sign Up</button>
            </div>
        </form>
    </div>
</div>
@endsection