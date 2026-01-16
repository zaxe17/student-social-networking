@extends('layout.app')

@section('page')
@include('layout.navbar')
<div class="container mx-auto flex items-start px-20 py-10 h-screen gap-15">
    <!-- USER PROFILE -->
    <div class="h-full px-18 py-13 overflow-scroll no-scrollbar">
        <!-- USER PROFILE -->
        <div class="flex justify-between items-center border-b border-b-[#770d08] mb-7 pb-10">
            <!-- USER IMAGE -->
            <div class="flex items-center text-3xl gap-6">
                <img src="/img/user.png" alt="" class="w-27 h-27">
                <div class="flex flex-col">
                    <!-- USER NAME -->
                    <div class="flex items-center gap-8">
                        <h2>Jan Marc Jacolbia</h2>
                        <span class="">BSIT | CCIS</span>
                    </div>

                    <!-- USER BIO -->
                    <span class="text-lg text-start italic">ako si jan marc mabato na ewan</span>
                </div>

            </div>

            <div class="flex items-center">
                <span class="icon transition-all duration-300 bg-black" style="--svg: url('https://api.iconify.design/mdi/instagram.svg'); --size: 28px; --icon-color: black;"></span>
                <span class="icon transition-all duration-300 bg-black" style="--svg: url('https://api.iconify.design/mdi/facebook.svg'); --size: 28px; --icon-color: black;"></span>
                <span class="icon transition-all duration-300 bg-black" style="--svg: url('https://api.iconify.design/mdi/linkedin.svg'); --size: 28px; --icon-color: black;"></span>
            </div>
        </div>
        <!-- USER POST -->
        @include('component.postcard')
    </div>
</div>
@endsection