<div id="menuOverlay" class="fixed left-0 top-18 w-full h-[calc(100vh-4.5rem)] bg-black/45 flex justify-end items-center z-40 backdrop-blur-[2px] hidden">

    <!-- SIDEBAR PANEL -->
    <div id="menuPanel"
        class="lg:w-1/4 w-full h-full bg-[#FFF9F5]/80 form-shadow backdrop-blur-sm flex flex-col translate-x-full transition-transform duration-300 ease-in-out">

        <!-- SIDEBAR LIST -->
        <ul class="flex flex-col lg:gap-8 gap-5 px-6 lg:py-15 py-10 justify-start flex-1">
            {{-- EDIT PROFILE BUTTON --}}
            <li
                class="px-2.5 py-3 mx-5 lg:text-xl text-base text-[#545454] hover:bg-[#770d08] hover:text-white transition-all duration-300 rounded-lg cursor-pointer"
                target-modal="editProfileModal"
                firstname-data="{{ $loggedInStudent->first_name ?? '' }}"
                lastname-data="{{ $loggedInStudent->last_name ?? '' }}"
                bio-data="{{ $loggedInStudent->bio ?? '' }}"
                instagram-data="{{ $loggedInStudent->instagram ?? '' }}"
                facebook-data="{{ $loggedInStudent->facebook ?? '' }}"
                linkedin-data="{{ $loggedInStudent->linkedin ?? '' }}"
                studentId-data="{{ $loggedInStudent->student_id ?? '' }}">
                <span>Edit Profile</span>
            </li>

            {{-- CHANGE PASSWORD --}}
            <li target-modal="changepassModal"
                class="px-2.5 py-3 mx-5 lg:text-xl text-base text-[#545454] hover:bg-[#770d08] hover:text-white transition-all duration-300 rounded-lg cursor-pointer">
                <span>Change Password</span>
            </li>

            {{-- ARCHIVE --}}
            <li class="px-2.5 py-3 mx-5 lg:text-xl text-base text-[#545454] hover:bg-[#770d08] hover:text-white transition-all duration-300 rounded-lg cursor-pointer"
                onclick="window.location.href=`{{ route('archived.page') }}`;">
                <span>Archive</span>
            </li>

            {{-- DELETE ACCOUNT --}}
            <li target-modal="deleteaccount"
                class="px-2.5 py-3 mx-5 lg:text-xl text-base text-[#545454] hover:bg-[#770d08] hover:text-white transition-all duration-300 rounded-lg cursor-pointer">
                <span>Delete Account</span>
            </li>
        </ul>

        <!-- USER PROFILE FOOTER -->
        <div class="w-full bg-[#bababa] px-16 py-10 border-t-2 border-t-[#770d08] flex items-center gap-5">
            <img onclick="window.location=`{{ route('profile.page') }}`;" src="{{ $loggedInStudent?->photo ? Storage::url($loggedInStudent->photo) : asset('/img/user.png') }}"
                class="lg:w-15 lg:h-15 w-10 h-10 rounded-full object-cover border cursor-pointer"
                alt="Profile picture">
            <div class="text-lg">
                <span>{{ $loggedInStudent->first_name ?? 'Guest' }}</span>
                <form method="POST" action="{{ route('students.logout') }}">
                    @csrf
                    <button type="submit" class="text-[#545454] underline block cursor-pointer">
                        Log-out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- INCLUDE EDIT PROFILE MODAL --}}
@include('component.editprofilemodal')

@include('component.changepassmodal', ['title' => 'Change password'])