<div class="shadow-bg px-10 pt-7 pb-4 mb-5">

    @php
    use Illuminate\Support\Str;

    // CATEGORY ICONS
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

    // LIKE STATUS
    $isLiked = $post->isLikedBy($student->student_id ?? null);

    // LIKE PREVIEW
    $likedUsers = $post->likesWithUser->pluck('student')->filter();
    $previewUsers = $likedUsers->take(5);
    $extraCount = max($likedUsers->count() - 5, 0);

    // COMMENT COUNT
    $commentsCount = $post->comments_count ?? 0;

    // CONTENT WITH CLICKABLE HASHTAGS
    $content = e($post->content);
    $content = preg_replace_callback('/#([\p{L}\p{N}_]+)/u', function($matches) {
    $tag = $matches[1];
    $url = route('hashtag.page', ['tag' => $tag]);
    return '<a href="' . $url . '" class="text-blue-600 hover:underline">#' . $tag . '</a>';
    }, $content);
    @endphp

    <!-- ================= HEADER ================= -->
    <div class="flex items-center gap-2 text-sm mb-5">

        <!-- AUTHOR PROFILE LINK + PHOTO -->
        <a href="{{ route('profile.view', $post->author->student_id) }}" class="flex items-center gap-2 hover:underline">
            <img src="{{ $post->author?->photo ? asset('storage/' . $post->author->photo) : asset('/img/user.png') }}"
                class="w-7 h-7 rounded-full object-cover border-2 border-gray-300" alt="">
            <span class="font-medium">{{ $post->author?->first_name }} {{ $post->author?->last_name }}</span>
        </a>

        <span>•</span>
        <span class="text-[#545454]">{{ $post->created_at?->diffForHumans() }}</span>

        <!-- CATEGORY -->
        @if ($post->category)
        @php
        $icon = $categoryIcons[$post->category->category_name] ?? 'mdi:tag';
        $categoryId = $post->category->category_id;
        $selectedCategories = request()->query('category', []);
        $queryCategories = in_array($categoryId, $selectedCategories)
        ? $selectedCategories
        : array_merge($selectedCategories, [$categoryId]);
        @endphp

        <span>•</span>
        <a href="{{ route('category.page', ['category' => $queryCategories]) }}"
            class="flex items-center gap-1 hover:underline">
            <span class="icon bg-[#770d08] mt-0.5"
                style="--svg: url('https://api.iconify.design/{{ $icon }}.svg'); --size: 18px;"></span>
            <span>{{ $post->category->category_name }}</span>
        </a>
        @endif

        <!-- RESTORE FORM -->
        <form id="restoreForm-{{ $post->post_id }}" action="{{ route('posts.restore', $post->post_id) }}" method="POST" class="hidden">
            @csrf
        </form>

        <!-- DROPDOWN MENU -->
        <div class="relative ml-auto">
            <span class="icon bg-[#545454] cursor-pointer dot-btn"
                data-dropdown="dotDropdown-{{ $post->post_id }}"
                style="--svg: url('https://api.iconify.design/solar/menu-dots-bold.svg'); --size: 25px;"></span>

            <div id="dotDropdown-{{ $post->post_id }}" class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg border border-gray-200 hidden z-50">
                <ul class="py-2 text-sm">
                    <!-- REPORT POST -->
                    @if ($student && $post->student_id !== $student->student_id)
                    <li target-modal="reportModal" postid-data="{{ $post->post_id }}"
                        class="px-4 py-2 cursor-pointer flex items-center gap-1.5 text-[#545454] hover:bg-gray-100">
                        <span class="icon bg-[#545454]" style="--svg: url('https://api.iconify.design/mdi/report.svg'); --size: 18px;"></span>
                        Report
                    </li>
                    @endif

                    <!-- EDIT / ARCHIVE / DELETE -->
                    @if ($student && $post->student_id === $student->student_id)
                    @if (request()->routeIs('archived.page'))
                    <li class="px-4 py-2 cursor-pointer flex items-center gap-1.5 text-[#545454] hover:bg-gray-100"
                        onclick="document.getElementById('restoreForm-{{ $post->post_id }}').submit();">
                        <span class="icon bg-[#545454]" style="--svg: url('https://api.iconify.design/mdi/restore.svg'); --size: 18px;"></span>
                        Restore
                    </li>
                    <li class="hover:bg-red-50">
                        <form action="{{ route('posts.forceDelete', $post->post_id) }}" method="POST"
                            onsubmit="return confirm('Permanently delete this post?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 flex items-center gap-1.5 text-red-600">
                                <span class="icon bg-red-600" style="--svg: url('https://api.iconify.design/mdi/delete-outline.svg'); --size: 18px;"></span>
                                Delete
                            </button>
                        </form>
                    </li>
                    @else
                    <li class="px-4 py-2 cursor-pointer flex items-center gap-1.5 text-[#545454] hover:bg-gray-100">
                        <span class="icon bg-[#545454]" style="--svg: url('https://api.iconify.design/mdi/edit-outline.svg'); --size: 18px;"></span>
                        <button type="button" class="editPostBtn text-left w-full"
                            data-post-id="{{ $post->post_id }}"
                            data-post-content="{{ e($post->content) }}">
                            Edit
                        </button>
                    </li>
                    <li class="px-4 py-2 cursor-pointer flex items-center gap-1.5 text-[#545454] hover:bg-gray-100">
                        <form action="{{ route('posts.destroy', $post->post_id) }}" method="POST" class="flex items-center gap-1.5 w-full">
                            @csrf
                            @method('DELETE')
                            <span class="icon bg-[#545454]" style="--svg: url('https://api.iconify.design/mdi/archive-outline.svg'); --size: 18px;"></span>
                            <button type="submit" class="w-full text-left">Archive</button>
                        </form>
                    </li>
                    <li class="hover:bg-red-50">
                        <form action="{{ route('posts.forceDelete', $post->post_id) }}" method="POST"
                            onsubmit="return confirm('Permanently delete this post?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 flex items-center gap-1.5 text-red-600">
                                <span class="icon bg-red-600" style="--svg: url('https://api.iconify.design/mdi/delete-outline.svg'); --size: 18px;"></span>
                                Delete
                            </button>
                        </form>
                    </li>
                    @endif
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- ================= CONTENT ================= -->
    <p class="border-b border-black/50 pb-5 mb-3.5">{!! nl2br($content) !!}</p>

    <!-- ================= COUNTS ================= -->
    <div class="flex justify-between items-center mb-3 text-sm text-[#545454]">

        <!-- LIKE COUNT + HOVER PREVIEW -->
        <button type="button" target-modal="reactorsModal" postid-data="{{ $post->post_id }}"
            class="relative group flex items-center gap-1 cursor-pointer">
            <span class="like-count" data-post-id="{{ $post->post_id }}">
                ❤️ {{ $post->likes_count }}
            </span>

            @if ($likedUsers->count())
            <div class="absolute left-0 top-full mt-2 w-56 bg-white border rounded-lg shadow-lg p-3 text-sm hidden group-hover:block z-50">
                @foreach ($previewUsers as $user)
                <div class="flex items-center gap-2 mb-1 pointer-events-none">
                    <img src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('/img/user.png') }}"
                        class="w-6 h-6 rounded-full object-cover">
                    <span>{{ $user->first_name }} {{ $user->last_name }}</span>
                </div>
                @endforeach
                @if ($extraCount > 0)
                <div class="text-xs text-gray-500 mt-1 pointer-events-none">
                    +{{ $extraCount }} more
                </div>
                @endif
            </div>
            @endif
        </button>

        <!-- COMMENT COUNT -->
        <span target-modal="commentModal"
            postid-data="{{ $post->post_id }}"
            firstname-data="{{ $post->author->first_name }}"
            lastname-data="{{ $post->author->last_name }}"
            timestamp-data="{{ $post->created_at->diffForHumans() }}"
            category-data="{{ $post->category->category_name ?? 'Uncategorized' }}"
            content-data="{{ e($post->content) }}"
            likes-data="{{ $post->likes_count }}"
            liked-data="{{ $post->isLikedBy($student->student_id) ? 1 : 0 }}"
            comment-data="{{ $commentsCount }}"
            userphoto-data="{{ $post->author->photo ? asset('storage/'.$post->author->photo) : asset('/img/user.png') }}"
            class="comment-count cursor-pointer hover:underline"
            data-post-id="{{ $post->post_id }}">
            {{ $commentsCount }} {{ Str::plural('comment', $commentsCount) }}
        </span>
    </div>

    <!-- ================= ACTION BUTTONS ================= -->
    <div class="flex justify-around items-center">
        <!-- REACT -->
        <button class="react-btn flex items-center gap-2 text-[#545454] hover:text-black transition" data-post-id="{{ $post->post_id }}">
            <span class="icon mt-1 {{ $isLiked ? 'bg-red-600' : 'bg-[#545454]' }}" data-heart-icon
                style="--svg: url('https://api.iconify.design/{{ $isLiked ? 'mdi:heart' : 'mdi:heart-outline' }}.svg'); --size: 24px;">
            </span>
            React
        </button>

        <!-- COMMENT -->
        <button target-modal="commentModal"
            postid-data="{{ $post->post_id }}"
            firstname-data="{{ $post->author->first_name }}"
            lastname-data="{{ $post->author->last_name }}"
            timestamp-data="{{ $post->created_at->diffForHumans() }}"
            category-data="{{ $post->category->category_name ?? 'Uncategorized' }}"
            content-data="{{ e($post->content) }}"
            likes-data="{{ $post->likes_count }}"
            liked-data="{{ $post->isLikedBy($student->student_id) ? 1 : 0 }}"
            comment-data="{{ $commentsCount }}"
            userphoto-data="{{ $post->author->photo ? asset('storage/'.$post->author->photo) : asset('/img/user.png') }}"
            class="flex items-center gap-2 text-[#545454] hover:text-black transition">
            <span class="icon mt-1 bg-[#545454]" style="--svg: url('https://api.iconify.design/mdi:comment-outline.svg'); --size: 22px;"></span>
            Comment
        </button>
    </div>
</div>