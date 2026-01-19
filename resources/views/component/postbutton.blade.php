<!-- INPUT AND PROFILE BOX -->
<div class="flex justify-center items-center mb-8 gap-5 h-10">
    <!-- USER PROFILE -->
    <img
        src="{{ auth()->check() && auth()->user()->photo
            ? asset('storage/' . auth()->user()->photo)
            : asset('/img/user.png') }}"
        class="w-10 h-10 rounded-full object-cover"
        alt="User">

    <input type="text" name="" target-modal="createPostModal" placeholder="create new post" class="w-full h-full px-2.5 rounded-sm bg-[#e7e8e9] focus:outline-none" readonly>
</div>