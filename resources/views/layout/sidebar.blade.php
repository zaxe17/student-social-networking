<div id="menuOverlay"
    class="fixed left-0 top-18 w-full h-[calc(100vh-4.5rem)] bg-black/45 flex justify-end items-center z-40 backdrop-blur-[2px] hidden">

    <!-- SIDEBAR PANEL -->
    <div id="menuPanel"
        class="w-1/4 h-full bg-[#FFF9F5]/80 form-shadow backdrop-blur-sm flex flex-col translate-x-full transition-transform duration-300 ease-in-out">

        <!-- SIDEBAR LIST -->
        <ul class="flex flex-col gap-8 px-6 py-15 justify-start flex-1">
            <li target-modal="editprofModal"
                studentId-data="{{ $student->student_id }}"
                firstname-data="{{ $student->first_name }}"
                lastname-data="{{ $student->last_name }}"
                bio-data="{{ $student->bio }}"
                instagram-data="{{ $student->instagram }}"
                facebook-data="{{ $student->facebook }}"
                linkedin-data="{{ $student->linkedin }}"
                class="px-2.5 py-3 mx-5 text-xl text-[#545454] hover:bg-[#770d08] hover:text-white transition-all duration-300 rounded-lg cursor-pointer">
                <span>Edit profile</span>
            </li>
            <li target-modal="changepassModal" class="px-2.5 py-3 mx-5 text-xl text-[#545454] hover:bg-[#770d08] hover:text-white transition-all duration-300 rounded-lg cursor-pointer">
                <span>Change password</span>
            </li>
            <li class="px-2.5 py-3 mx-5 text-xl text-[#545454] hover:bg-[#770d08] hover:text-white transition-all duration-300 rounded-lg cursor-pointer" onclick="window.location.href=`{{ route('archived.page') }}`;">
                <span>Archive</span>
            </li>
            <li class="px-2.5 py-3 mx-5 text-xl text-[#545454] hover:bg-[#770d08] hover:text-white transition-all duration-300 rounded-lg cursor-pointer">
                <span>Delete account</span>
            </li>
        </ul>

        <!-- USER PROFILE FOOTER -->
        <div class="w-full bg-[#bababa] px-16 py-10 border-t-2 border-t-[#770d08] flex items-center gap-5">
            <img src="/img/user.png" class="w-13 h-13 rounded-full object-cover cursor-pointer border-2 border-white">
            <div class="text-lg">
                <span>{{ $student->first_name ?? 'Guest' }}</span>
                <form method="POST" action="{{ route('students.logout') }}">
                    @csrf
                    <button type="submit" class="text-[#545454] underline block">Log-out</button>
                </form>
            </div>
        </div>
    </div>
</div>