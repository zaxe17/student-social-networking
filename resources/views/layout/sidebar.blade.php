@php
    $studentId = session('student_id');
    $student = $studentId ? \App\Models\Student::find($studentId) : null;
@endphp

@if($student)
<div id="menuOverlay"
     class="fixed left-0 top-[4.5rem] w-full h-[calc(100vh-4.5rem)] bg-black/45 flex justify-end items-center z-40 backdrop-blur-[2px] hidden">
    <div id="menuPanel" class="w-1/4 h-full bg-[#FFF9F5]/80 form-shadow backdrop-blur-sm flex flex-col translate-x-full transition-transform duration-300 ease-in-out">

        <ul class="flex flex-col gap-8 px-6 py-15 justify-start flex-1">
            <li target-modal="editprofModal"
                class="px-2.5 py-3 mx-5 text-xl text-[#545454] hover:bg-[#770d08] hover:text-white transition-all duration-300 rounded-lg cursor-pointer">
                Edit profile
            </li>
            <li target-modal="changepassModal"
                class="px-2.5 py-3 mx-5 text-xl text-[#545454] hover:bg-[#770d08] hover:text-white transition-all duration-300 rounded-lg cursor-pointer">
                Change password
            </li>
            <li onclick="window.location.href='{{ route('archived.page') }}'"
                class="px-2.5 py-3 mx-5 text-xl text-[#545454] hover:bg-[#770d08] hover:text-white transition-all duration-300 rounded-lg cursor-pointer">
                View archive posts
            </li>
        </ul>

        <div class="w-full bg-[#bababa] px-16 py-10 border-t-2 border-t-[#770d08] flex items-center gap-5">
            <img src="{{ $student->photo ? asset('storage/' . $student->photo) : '/img/user.png' }}" class="w-13 h-13">
            <div class="text-lg">
                <div class="font-semibold">{{ $student->first_name }} {{ $student->last_name }}</div>
                <span class="block text-[#545454]">{{ $student->student_id }}</span>

                <form action="{{ route('students.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-[#545454] underline block">Log-out</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endif
