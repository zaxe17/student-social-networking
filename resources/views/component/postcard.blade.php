<div class="shadow-bg px-10 pt-7 pb-4 mb-5">

    @php
    $categoryIcons = [
    'Announcements' => 'mdi:bullhorn',
    'Events' => 'mdi:calendar-star',
    'Discussions' => 'mdi:forum',
    'Help' => 'mdi:help-circle',
    'Achievements' => 'mdi:trophy',
    'Lost & Found' => 'mdi:magnify',
    'Marketplace' => 'mdi:store',
    'Clubs & Organizations' => 'mdi:account-group',
    'Entertainment' => 'mdi:movie-open',
    'Miscellaneous' => 'mdi:dots-horizontal',
    ];
    @endphp

    <!-- HEADER -->
    <div class="flex items-center gap-2 text-sm mb-5">

        {{-- User photo --}}
        <img src="{{ $post->author?->photo ? asset('storage/' . $post->author->photo) : asset('/img/user.png') }}"
            class="w-7 h-7 rounded-full object-cover cursor-pointer border-2 border-gray-300" alt="">

        {{-- Student name --}}
        <span>{{ $post->author?->first_name }} {{ $post->author?->last_name }}</span>

        <span>•</span>

        {{-- Timestamp --}}
        <span class="text-[#545454]">{{ $post->created_at?->diffForHumans() }}</span>

        {{-- Category --}}
        @if ($post->category)
        @php
        $icon = $categoryIcons[$post->category->category_name] ?? 'mdi:tag';
        $categoryId = $post->category->category_id;
        $selectedCategories = request()->query('category', []);
        // Merge current category with already selected for redirect
        $queryCategories = in_array($categoryId, $selectedCategories) ? $selectedCategories : array_merge($selectedCategories, [$categoryId]);
        @endphp

        <span>•</span>

        <a href="{{ route('category.page', ['category' => $queryCategories]) }}" class="flex items-center gap-1 hover:underline">
            <span class="icon bg-[#770d08] mt-0.5" style="--svg: url('https://api.iconify.design/{{ $icon }}.svg'); --size: 18px;"></span>
            <span>{{ $post->category->category_name }}</span>
        </a>
        @endif

        <form id="restoreForm-{{ $post->post_id }}" action="{{ route('posts.restore', $post->post_id) }}" method="POST" class="hidden">
            @csrf
        </form>

        {{-- Dropdown --}}
        <div class="relative ml-auto">
            <span class="icon bg-[#545454] cursor-pointer dot-btn"
                data-dropdown="dotDropdown-{{ $post->post_id }}"
                style="--svg: url('https://api.iconify.design/solar/menu-dots-bold.svg'); --size: 25px;">
            </span>

            <div id="dotDropdown-{{ $post->post_id }}" class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg border border-gray-200 hidden z-50">
                <ul class="py-2 text-sm">
                    @if ($student && $post->student_id !== $student->student_id)
                    <li target-modal="reportModal" class="px-4 py-2 cursor-pointer flex items-center gap-1.5 text-[#545454]">
                        <span class="icon bg-[#545454] mt-0.5" style="--svg: url('https://api.iconify.design/mdi/report.svg'); --size: 18px;"></span>
                        Report
                    </li>
                    @endif

                    @if ($student && $post->student_id === $student->student_id)
                    <li class="px-4 py-2 cursor-pointer flex items-center gap-1.5 text-[#545454] {{ Route::currentRouteName() == 'archived.page' ? 'hidden' : '' }}">
                        <span class="icon bg-[#545454] mt-0.5" style="--svg: url('https://api.iconify.design/mdi/edit-outline.svg'); --size: 18px;"></span>
                        Edit
                    </li>

                    <li class="px-4 py-2 cursor-pointer flex items-center gap-1.5 text-[#545454] {{ Route::currentRouteName() == 'archived.page' ? 'hidden' : '' }}">
                        <form action="{{ route('posts.destroy', $post->post_id) }}" method="POST" class="w-full flex items-center gap-1.5">
                            @csrf
                            @method('DELETE')
                            <span class="icon bg-[#545454] mt-0.5" style="--svg: url('https://api.iconify.design/mdi/archive-outline.svg'); --size: 18px;"></span>
                            <button type="submit" class="w-full text-left">Archive</button>
                        </form>
                    </li>

                    {{-- RESTORE button --}}
                    <li class="px-4 py-2 cursor-pointer flex items-center gap-1.5 text-[#545454] {{ Route::currentRouteName() == 'archived.page' ? '' : 'hidden' }}" onclick="event.preventDefault(); document.getElementById('restoreForm-{{ $post->post_id }}').submit();">
                        <span class="icon bg-[#545454] mt-0.5" style="--svg: url('https://api.iconify.design/ic/round-restore.svg'); --size: 18px;"></span>
                        restore
                    </li>

                    <li class="px-4 py-2 cursor-pointer flex items-center gap-1.5 text-red-600">
                        <form action="{{ route('posts.forceDelete', $post->post_id) }}" method="POST" class="w-full flex items-center gap-1.5">
                            @csrf
                            @method('DELETE')
                            <span class="icon bg-red-600 mt-0.5" style="--svg: url('https://api.iconify.design/mdi/delete-outline.svg'); --size: 18px;"></span>
                            <button type="submit" class="w-full text-left">Delete</button>
                        </form>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    @php
    $content = e($post->content);
    $content = preg_replace('/#([\p{L}\p{N}_]+)/u', '<span class="text-blue-600">#$1</span>', $content);
    @endphp

    <p class="border-b border-black/50 pb-5 mb-3.5">{!! nl2br($content) !!}</p>

    <!-- REACT / COMMENT COUNT -->
    <div class="flex justify-between items-center mb-3">
        <form action="{{ route('posts.like', $post->post_id) }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center gap-1 cursor-pointer">
                <span>❤️</span>
                <span class="text-sm text-[#545454]">{{ $post->likes_count ?? 0 }}</span>
            </button>
        </form>

        <span target-modal="commentModal" class="text-[#545454] text-sm cursor-pointer hover:underline">
            {{ $post->comments_count ?? 0 }} comment{{ ($post->comments_count ?? 0) > 1 ? 's' : '' }}
        </span>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="flex justify-around items-center">
        <button class="flex items-center gap-2 text-[#545454] cursor-pointer">
            <span class="icon bg-[#545454] mt-1" style="--svg: url('https://api.iconify.design/mdi/heart-outline.svg'); --size: 22px;"></span>
            react
        </button>

        <button target-modal="commentModal"
            studentId-data="{{ $post->student_id }}"
            firstname-data="{{ $post->author?->first_name }}"
            lastname-data="{{ $post->author?->last_name }}"
            timestamp-data="{{ $post->created_at?->diffForHumans() }}"
            category-data="{{ $post->category?->category_name ?? 'Uncategorized' }}"
            content-data='{!! nl2br(e($content)) !!}'
            likes-data="{{ $post->likes_count ?? 0 }}"
            comment-data="{{ $post->comments_count ?? 0 }} comment{{ ($post->comments_count ?? 0) > 1 ? 's' : '' }}"
            class="flex items-center gap-2 text-[#545454] cursor-pointer">

            <span class="icon bg-[#545454] mt-1"
                style="--svg: url('https://api.iconify.design/mdi/comment-outline.svg'); --size: 22px;"></span>
            comment
        </button>
    </div>
</div>