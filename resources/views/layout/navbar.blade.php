@php
    $studentId = session('student_id');
    $student = $studentId ? \App\Models\Student::find($studentId) : null;
@endphp

<nav class="shadow-nav sticky top-0 w-full h-18 bg-[#770d08] px-13 flex justify-between items-center z-50">
    <!-- LOGO -->
    <a href="{{ route('feed.page') }}" class="flex items-center gap-4">
        <img src="/img/logo.png" alt="pup_logo" class="w-10 h-10">
        <p class="text-2xl text-white font-bold">ISKOnnect</p>
    </a>

    <!-- PAGE LIST ICON -->
    <div class="flex items-center h-full gap-3">
        <!-- FEED PAGE -->
        <a href="{{ route('feed.page') }}" class="group border-b-2 border-solid transition-all duration-300 w-35 h-full flex justify-center items-center
            {{ (Route::currentRouteName() == 'feed.page' || Route::currentRouteName() == 'category.page') ? 'border-b-white' : 'border-b-transparent hover:border-b-white' }}">
            <div class="flex justify-center transition-all duration-300 rounded-lg w-full p-4
                {{ (Route::currentRouteName() == 'feed.page' || Route::currentRouteName() == 'category.page') ? '' : 'group-hover:bg-white/10' }}">
                <span class="icon transition-all duration-300 bg-white" style="--svg: url('https://api.iconify.design/ion/newspaper-outline.svg'); --size: 24px; --icon-color: black;"></span>
            </div>
        </a>

        <!-- PROFILE PAGE -->
        <a href="{{ route('profile.page') }}" class="group border-b-2 border-solid border-b-transparent hover:border-b-white transition-all duration-300 w-35 h-full flex justify-center items-center
            {{ Route::currentRouteName() == 'profile.page' ? 'border-b-white' : 'border-b-transparent hover:border-b-white' }}">
            <div class="flex justify-center group-hover:bg-white/10 transition-all duration-300 rounded-lg w-full p-4">
                <span class="icon bg-white" style="--svg: url('https://api.iconify.design/mdi/user.svg'); --size: 28px; --icon-color: black;"></span>
            </div>
        </a>
    </div>

    <!-- RIGHT SIDE -->
    <div class="flex items-center gap-4">
        <!-- SEARCH BAR -->
        <div class="relative shadow-input w-60 h-8 px-2.5 bg-white flex items-center rounded-sm">
            <input type="text" id="searchInput" class="w-full text-sm focus:outline-none" placeholder="Search...">
            <span class="icon bg-[#545454] transition-all duration-300" style="--svg: url('https://api.iconify.design/mdi/search.svg'); --size: 20px; --icon-color: black;"></span>

            <!-- SEARCH RESULTS DROPDOWN -->
            <div id="searchResults" class="absolute top-full left-0 w-full bg-white border border-gray-300 rounded-b-md shadow-lg max-h-60 overflow-y-auto hidden z-50"></div>
        </div>

        <!-- PROFILE PICTURE -->
        @if($student)
        <img src="{{ $student->photo ? asset('storage/'.$student->photo) : asset('/img/user.png') }}"
            class="w-8 h-8 rounded-full object-cover cursor-pointer border-2 border-white"
            id="menuBtn"
            alt="Profile">
        @else
        <img src="/img/user.png"
            class="w-8 h-8 rounded-full object-cover cursor-pointer border-2 border-white"
            id="menuBtn"
            alt="Profile">
        @endif
    </div>
</nav>

<!-- SEARCH SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const dropdown = document.getElementById('searchResults');
    let timeout = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value.trim();

        if (!query) {
            dropdown.classList.add('hidden');
            dropdown.innerHTML = '';
            return;
        }

        timeout = setTimeout(async () => {
            try {
                const res = await fetch(`/search?q=${encodeURIComponent(query)}`);
                const data = await res.json();
                let html = '';

                if (data.profiles.length) {
                    html += `<div class="px-4 py-2 border-b font-semibold">Profiles</div>`;
                    data.profiles.forEach(profile => {
                        html += `<a href="/profile/${profile.student_id}" class="block px-4 py-2 hover:bg-gray-100">${profile.name}</a>`;
                    });
                }

                if (data.posts.length) {
                    html += `<div class="px-4 py-2 border-b font-semibold">Posts</div>`;
                    data.posts.forEach(post => {
                        html += `<a href="/search/results?q=${encodeURIComponent(post.content)}" class="block px-4 py-2 hover:bg-gray-100">${post.short_content}</a>`;
                    });
                }

                if (!html) html = `<div class="px-4 py-2">No results</div>`;

                dropdown.innerHTML = html;
                dropdown.classList.remove('hidden');
            } catch (err) {
                console.error(err);
            }
        }, 300);
    });

    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
});
</script>
