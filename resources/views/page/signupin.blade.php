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
        <form action="" method="" class="bg-[#f4f6f9]/60 rounded-3xl w-md h-full p-8 gap-1">
            <div class="flex justify-center items-center flex-col gap-1.5">
                <h1 class="text-center font-medium text-3xl">Registration</h1>
                <div class="grid grid-cols-12 gap-4">
                    <!-- Student ID (col-span-12) -->
                    <div class="flex flex-col col-span-12">
                        <label for="">Student ID</label>
                        <input type="text" name="" id="" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none">
                    </div>

                    <!-- First Name (col-span-6) -->
                    <div class="flex flex-col col-span-6">
                        <label for="">First Name</label>
                        <input type="text" name="" id="" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none">
                    </div>

                    <!-- Last Name (col-span-6) -->
                    <div class="flex flex-col col-span-6">
                        <label for="">Last Name</label>
                        <input type="text" name="" id="" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none">
                    </div>

                    <!-- Birthday (col-span-4) -->
                    <div class="flex flex-col col-span-4">
                        <label for="">Birthday</label>
                        <input type="text" name="" placeholder="yyyy" id="" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none">
                    </div>

                    <!-- Empty field 1 (col-span-4) -->
                    <div class="flex flex-col col-span-4">
                        <label for="">&nbsp;</label>
                        <input type="text" name="" placeholder="mm" id="" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none">
                    </div>

                    <!-- Empty field 2 (col-span-4) -->
                    <div class="flex flex-col col-span-4">
                        <label for="">&nbsp;</label>
                        <input type="text" name="" placeholder="dd" id="" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none">
                    </div>

                    <!-- Year Level (col-span-4) -->
                    <div class="flex flex-col col-span-4">
                        <label for="">Year Level</label>
                        <select name="" id="" class="text-sm bg-[#dde0e5] w-full h-8 rounded-sm focus:outline-none">
                            <option value="">1st year</option>
                            <option value="">2nd year</option>
                            <option value="">3rd year</option>
                            <option value="">4th year</option>
                            <option value="">5th year</option>
                        </select>
                    </div>

                    <!-- Course (col-span-8) -->
                    <div class="flex flex-col col-span-8">
                        <label for="">Course</label>
                        <input type="text" name="" id="" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none">
                    </div>

                    <!-- Year Level (col-span-12) -->
                    <div class="flex flex-col col-span-12">
                        <label for="">Create Password</label>
                        <input type="text" name="" id="" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none">
                    </div>

                    <!-- Course (col-span-12) -->
                    <div class="flex flex-col col-span-12">
                        <label for="">Confirm Password</label>
                        <input type="text" name="" id="" class="bg-[#dde0e5] w-full h-8 p-2 rounded-sm focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-center mt-5">
                    <button type="submit" class="bg-[#770d08] w-30 text-white font-medium rounded-md py-1.5">Sign Up</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection