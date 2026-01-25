@php
// Get the logged-in student's ID from session
$loggedId = session('student_id');

// Check if the logged-in student is the author of this comment
$isOwner = $loggedId && ((string)$comment->student_id === (string)$loggedId);

// Support either id or comment_id
$commentId = $comment->comment_id ?? $comment->id;
@endphp

<div class="comment-item flex items-start gap-2 mb-4 px-8"
    data-comment-id="{{ $commentId }}"
    data-post-id="{{ $comment->post_id }}">

    {{-- Author photo --}}
    <img src="{{ $comment->author?->photo ? asset('storage/'.$comment->author->photo) : asset('/img/user.png') }}"
        class="w-8 h-8 rounded-full object-cover border-2 border-gray-300"
        alt="User photo">

    {{-- Comment content --}}
    <div class="flex-1 text-sm">
        <div class="flex items-center justify-between gap-2">
            <div>
                <span class="font-bold">
                    {{ $comment->author?->first_name }} {{ $comment->author?->last_name }}
                </span>

                <span class="text-xs text-gray-500 ml-2 comment-updated">
                    {{ $comment->updated_at ? $comment->updated_at->diffForHumans() : '' }}
                </span>
            </div>

            {{-- Edit/Delete buttons only for comment owner --}}
            @if($isOwner)
            <div class="relative ml-auto comment-dropdown-wrapper">
                <!-- Dot button -->
                <span class="icon bg-[#545454] cursor-pointer mt-1.5 dot-btn"
                    style="--svg: url('https://api.iconify.design/solar/menu-dots-bold.svg'); --size: 25px;"></span>

                <!-- Dropdown menu -->
                <div class="dropdown-menu absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg border border-gray-200 hidden z-50">
                    <ul class="py-2 text-sm">
                        <li class="px-4 py-2 cursor-pointer flex items-center gap-1.5 text-[#545454] hover:bg-gray-100">
                            <button type="button" class="btn-edit-comment cursor-pointer flex items-center gap-1.5">
                                <span class="icon bg-[#545454]" style="--svg: url('https://api.iconify.design/mdi/edit-outline.svg'); --size: 18px;"></span>
                                Edit
                            </button>
                        </li>
                        <li class="px-4 py-2 cursor-pointer flex items-center gap-1.5 text-[#545454] hover:bg-gray-100" target-modal="deletecomment">
                            <button type="button" class="btn-delete-comment cursor-pointer flex items-center gap-1.5 text-red-600">
                                <span class="icon bg-red-600" style="--svg: url('https://api.iconify.design/mdi/delete-outline.svg'); --size: 18px;"></span>
                                Delete
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            @endif
        </div>

        {{-- Comment text --}}
        <p class="comment-text mt-1">{{ $comment->content }}</p>

        {{-- Inline edit form (hidden by default) --}}
        @if($isOwner)
        <div class="comment-edit-box hidden mt-2">
            <input type="text"
                class="comment-input w-full bg-[#dde0e5] px-2 py-1 rounded focus:outline-none"
                value="{{ $comment->content }}">

            <div class="flex gap-2 mt-2">
                <button type="button"
                    class="btn-save-comment bg-[#770d08] text-white px-3 py-1 rounded">
                    Save
                </button>

                <button type="button"
                    class="btn-cancel-edit text-gray-600 hover:underline">
                    Cancel
                </button>
            </div>

            <p class="comment-error text-red-600 text-xs mt-1 hidden"></p>
        </div>
        @endif
    </div>
</div>