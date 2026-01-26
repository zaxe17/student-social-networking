<!-- INPUT AND PROFILE BOX -->
<div class="flex justify-center items-center mb-8 gap-5 h-10">
    <!-- STUDENT PROFILE -->
    <img id="sidebarProfilePic" src="{{ $loggedInStudent->photo ? asset('storage/'.$loggedInStudent->photo) : asset('/img/user.png') }}" alt="Profile Picture" class="w-10 h-10 rounded-full object-cover cursor-pointer border-2 border-gray-300">

    <!-- CREATE POST BUTTON -->
    <input type="text" name="" target-modal="createPostModal" placeholder="Create New Post" class="w-full h-full px-2.5 rounded-sm bg-[#e7e8e9] focus:outline-none" readonly>
</div>