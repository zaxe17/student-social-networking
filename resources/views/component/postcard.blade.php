<div class="shadow-bg lg:px-10 lg:pt-7 lg:pb-4 p-5 mb-5 rounded-xl">

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

    <!-- HEADER -->
    <div class="flex items-center gap-2 text-sm mb-5">

        <!-- STUDENT PROFILE LINK + PHOTO -->
        <div class="flex items-center gap-2">
            <!-- STUDENT PROFILE -->
            <a href="{{ route('profile.view', $post->author->student_id) }}" class="w-8 h-8">
                <img src="{{ $post->author?->photo ? asset('/storage/' . $post->author->photo) : asset('/img/user.png') }}"
                    class="w-full h-full rounded-full object-cover border-2 border-gray-300" alt="">
            </a>
            <div class="flex flex-col">
                <!-- STUDENT NAME -->
                <a href="{{ route('profile.view', $post->author->student_id) }}">
                    <span class="font-medium">{{ $post->author?->first_name }} {{ $post->author?->last_name }}</span>
                </a>
                <div class="flex items-center gap-2 lg:text-xs text-[10px]">
                    <!-- TIMESTAMP -->
                    <span class="text-[#545454]">{{ $post->created_at?->diffForHumans() }}</span>

                    <!-- CATEGORY ICON -->
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
                    <!-- CATEGORY NAME AND ICON -->
                    <a href="{{ route('category.page', ['category' => $queryCategories]) }}"
                        class="flex items-center gap-1 hover:underline">
                        <span class="icon bg-[#770d08]"
                            style="--svg: url('https://api.iconify.design/{{ $icon }}.svg'); --size: 14px;"></span>
                        <span class="text-[#545454]">{{ $post->category->category_name }}</span>
                    </a>
                    @endif
                </div>

            </div>
        </div>

        <!-- RESTORE FORM -->
        <form id="restoreForm-{{ $post->post_id }}" action="{{ route('posts.restore', $post->post_id) }}" method="POST" class="hidden">
            @csrf
        </form>

        <!-- DROPDOWN MENU -->
        <div class="relative ml-auto">
            <span class="icon bg-[#545454] cursor-pointer mt-1.5 dot-btn"
                data-dropdown="dotDropdown-{{ $post->post_id }}"
                style="--svg: url('https://api.iconify.design/solar/menu-dots-bold.svg'); --size: 25px;"></span>

            <!-- ACTION POST BUTTON MENU -->
            <div id="dotDropdown-{{ $post->post_id }}" class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg border border-gray-200 hidden z-50">
                <ul class="py-2 text-sm">
                    <!-- REPORT POST -->
                    @if ($student && $post->student_id !== $student->student_id)
                    <li target-modal="reportModal" postid-data="{{ $post->post_id }}"
                        class="px-4 py-2 cursor-pointer flex items-center gap-1.5 text-[#545454] transition-all duration-300 ease-in-out hover:bg-black/5">
                        <span class="icon bg-[#545454]" style="--svg: url('https://api.iconify.design/mdi/report.svg'); --size: 18px;"></span>
                        Report
                    </li>
                    @endif

                    <!-- MENU BUTTON WHILE IN ARCHIVED PAGE -->
                    @if ($student && $post->student_id === $student->student_id)
                    @if (request()->routeIs('archived.page'))
                    <!-- RESTORE BUTTON FOR ARCHIVED POST -->
                    <li class="px-4 py-2 cursor-pointer flex items-center gap-1.5 text-[#545454] transition-all duration-300 ease-in-out hover:bg-black/10"
                        onclick="document.getElementById('restoreForm-{{ $post->post_id }}').submit();">
                        <span class="icon bg-[#545454]" style="--svg: url('https://api.iconify.design/mdi/restore.svg'); --size: 18px;"></span>
                        Restore
                    </li>
                    <!-- DELETE BUTTON IF IN ARCHIVE PAGE -->
                    <li class="cursor-pointer transition-all duration-300 ease-in-out hover:bg-red-600/10" target-modal="deletepost{{ $post->post_id }}">
                        <button type="button" class="w-full cursor-pointer px-4 py-2 flex items-center gap-1.5 text-red-600">
                            <span class="icon bg-red-600" style="--svg: url('https://api.iconify.design/mdi/delete-outline.svg'); --size: 18px;"></span>
                            Delete
                        </button>
                    </li>
                    @else
                    <!-- EDIT BUTTON -->
                    <li class="px-4 py-2 cursor-pointer text-[#545454] transition-all duration-300 ease-in-out hover:bg-black/10">
                        <button type="button" class="editPostBtn text-left w-full cursor-pointer flex items-center gap-1.5"
                            data-post-id="{{ $post->post_id }}"
                            data-post-content="{{ e($post->content) }}">
                            <span class="icon bg-[#545454]" style="--svg: url('https://api.iconify.design/mdi/edit-outline.svg'); --size: 18px;"></span>
                            Edit
                        </button>
                    </li>
                    <!-- ARCHIVED BUTTON -->
                    <li class="px-4 py-2 cursor-pointer flex items-center gap-1.5 text-[#545454] transition-all duration-300 ease-in-out hover:bg-black/10">
                        <form action="{{ route('posts.destroy', $post->post_id) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full cursor-pointer text-left flex items-center gap-1.5">
                                @method('DELETE')
                                <span class="icon bg-[#545454]" style="--svg: url('https://api.iconify.design/mdi/archive-outline.svg'); --size: 18px;"></span>
                                Archive
                            </button>
                        </form>
                    </li>
                    <!-- DELETE BUTTON -->
                    <li class="cursor-pointer transition-all duration-300 ease-in-out hover:bg-red-600/10" target-modal="deletepost{{ $post->post_id }}">
                        <button type="button" class="w-full cursor-pointer px-4 py-2 flex items-center gap-1.5 text-red-600">
                            <span class="icon bg-red-600" style="--svg: url('https://api.iconify.design/mdi/delete-outline.svg'); --size: 18px;"></span>
                            Delete
                        </button>
                    </li>
                    @endif
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- CONTENT POST -->
    <p class="border-b border-black/40 pb-5 mb-1.5 wrap-break-word">{!! nl2br($content) !!}</p>

    <!-- REACT AND COMMENT COUNT -->
    <div class="flex justify-between items-center mb-1.5 text-sm text-[#545454]">

        <!-- LIKE COUNT -->
        <button type="button" target-modal="reactorsModal" postid-data="{{ $post->post_id }}"
            class="like-count-container relative group flex items-center gap-1 cursor-pointer {{ $post->likes_count === 0 ? 'invisible' : '' }}"
            data-post-id="{{ $post->post_id }}">
            <span class="like-count" data-post-id="{{ $post->post_id }}">
                ❤️ {{ $post->likes_count }}
            </span>

            <!-- SHOW THE STUDENT WHO REACT THIS POST -->
            @if ($likedUsers->count())
            <div class="absolute left-0 top-full mt-2 w-56 bg-white border rounded-lg shadow-lg p-3 text-sm hidden group-hover:block z-50">
                @foreach ($previewUsers as $user)
                <div class="flex items-center gap-2 mb-1 pointer-events-none">
                    <img src="{{ $user->photo ? asset('/storage/'.$user->photo) : asset('/img/user.png') }}"
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

        <!-- COMMENT COUNT BUTTON WITH PASSING DATA IN JS TO DISPLAY DATA IN POSTMODAL BLADE -->
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
            class="comment-count cursor-pointer hover:underline {{ $commentsCount === 0 ? 'invisible' : '' }}"
            data-post-id="{{ $post->post_id }}">
            {{ $commentsCount }} {{ $commentsCount == 1 ? 'comment' : 'comments' }}
        </span>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="flex justify-around items-center">
        <!-- REACT BUTTON -->
        <button class="react-btn group flex items-center gap-2 text-[#545454] lg:text-base text-sm transition-all duration-300 ease-in-out hover:text-black cursor-pointer" data-post-id="{{ $post->post_id }}">
            <span class="icon mt-0.5 transition-transform duration-150 ease-in-out group-hover:bg-red-600 {{ $isLiked ? 'bg-red-600' : 'bg-[#545454]' }}" data-heart-icon
                style="--svg: url('https://api.iconify.design/{{ $isLiked ? 'mdi:heart' : 'mdi:heart-outline' }}.svg'); --size: 24px;">
            </span>
            React
        </button>

        <!-- COMMENT BUTTON WITH PASSING DATA IN JS TO DISPLAY DATA IN POSTMODAL BLADE -->
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
            class="flex items-center gap-2 text-[#545454] lg:text-base text-sm transition-all duration-300 ease-in-out hover:text-black cursor-pointer">
            <span class="icon mt-0.5 bg-[#545454]" style="--svg: url('https://api.iconify.design/mdi:comment-outline.svg'); --size: 22px;"></span>
            Comment
        </button>
    </div>
</div>