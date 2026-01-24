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

    $isLiked = $post->isLikedBy($student->student_id ?? null);
    $likedUsers = $post->likesWithUser->pluck('student')->filter();
    $previewUsers = $likedUsers->take(5);
    $extraCount = max($likedUsers->count() - 5, 0);
    @endphp

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center gap-2 text-sm mb-5">

        {{-- AUTHOR PROFILE --}}
        <a href="{{ route('profile.view', $post->author->student_id) }}" class="flex items-center gap-2 hover:underline">
            <img src="{{ $post->author?->photo ? asset('storage/' . $post->author->photo) : asset('/img/user.png') }}"
                 class="w-7 h-7 rounded-full object-cover border-2 border-gray-300" alt="">
            <span class="font-medium">{{ $post->author?->first_name }} {{ $post->author?->last_name }}</span>
        </a>

        <span>•</span>
        <span class="text-[#545454]">{{ $post->created_at?->diffForHumans() }}</span>

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
    </div>

    {{-- ================= CONTENT ================= --}}
    @php
        $content = e($post->content);
        $content = preg_replace('/#([\p{L}\p{N}_]+)/u', '<span class="text-blue-600">#$1</span>', $content);
    @endphp
    <p class="border-b border-black/50 pb-5 mb-3.5">{!! nl2br($content) !!}</p>

    {{-- ================= COUNTS ================= --}}
    <div class="flex justify-between items-center mb-3 text-sm text-[#545454]">

        {{-- LIKE COUNT --}}
        <button type="button"
                target-modal="commentModal"
                postid-data="{{ $post->post_id }}"
                class="relative group flex items-center gap-1 cursor-pointer">
            <span class="like-count" data-post-id="{{ $post->post_id }}">
                ❤️ {{ $post->likes_count }}
            </span>

            @if ($likedUsers->count())
            <div class="absolute left-0 top-full mt-2 w-56 bg-white border rounded-lg shadow-lg p-3
                        text-sm hidden group-hover:block z-50">
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

        {{-- COMMENTS COUNT --}}
        <span
            target-modal="commentModal"
            postid-data="{{ $post->post_id }}"
            firstname-data="{{ $post->author->first_name }}"
            lastname-data="{{ $post->author->last_name }}"
            timestamp-data="{{ $post->created_at->diffForHumans() }}"
            category-data="{{ $post->category->category_name ?? 'Uncategorized' }}"
            content-data="{{ e($post->content) }}"
            likes-data="{{ $post->likes_count }}"
            liked-data="{{ $post->isLikedBy($student->student_id) ? 1 : 0 }}"
            comment-data="{{ $post->comments_count }}"
            userphoto-data="{{ $post->author->photo ? asset('storage/'.$post->author->photo) : asset('/img/user.png') }}"
            class="cursor-pointer hover:underline">
            {{ $post->comments_count }} comment{{ $post->comments_count > 1 ? 's' : '' }}
        </span>
    </div>

    {{-- ================= ACTION BUTTONS ================= --}}
    <div class="flex justify-around items-center">
        {{-- REACT BUTTON --}}
        <button
            class="react-btn flex items-center gap-2 text-[#545454] hover:text-black transition"
            data-post-id="{{ $post->post_id }}">
            <span
                class="icon mt-1 {{ $isLiked ? 'bg-red-600' : 'bg-[#545454]' }}"
                data-heart-icon
                style="--svg: url('https://api.iconify.design/{{ $isLiked ? 'mdi:heart' : 'mdi:heart-outline' }}.svg'); --size: 24px;">
            </span>
            React
        </button>

        {{-- COMMENT BUTTON --}}
        <button
            target-modal="commentModal"
            postid-data="{{ $post->post_id }}"
            firstname-data="{{ $post->author->first_name }}"
            lastname-data="{{ $post->author->last_name }}"
            timestamp-data="{{ $post->created_at->diffForHumans() }}"
            category-data="{{ $post->category->category_name ?? 'Uncategorized' }}"
            content-data="{{ e($post->content) }}"
            likes-data="{{ $post->likes_count }}"
            liked-data="{{ $post->isLikedBy($student->student_id) ? 1 : 0 }}"
            comment-data="{{ $post->comments_count }}"
            userphoto-data="{{ $post->author->photo ? asset('storage/'.$post->author->photo) : asset('/img/user.png') }}"
            class="flex items-center gap-2 text-[#545454] hover:text-black transition">
            <span
                class="icon mt-1 bg-[#545454]"
                style="--svg: url('https://api.iconify.design/mdi/comment-outline.svg'); --size: 22px;">
            </span>
            Comment
        </button>
    </div>
</div>