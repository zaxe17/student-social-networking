@php
    $loggedId = session('student_id');
    $isOwner = $loggedId && ((string)$comment->student_id === (string)$loggedId);

    // ✅ supports id OR comment_id
    $commentId = $comment->comment_id ?? $comment->id;
@endphp

<div class="comment-item flex items-start gap-2 mb-4 px-8"
     data-comment-id="{{ $commentId }}"
     data-post-id="{{ $comment->post_id }}">

    <img src="{{ $comment->author?->photo ? asset('storage/'.$comment->author->photo) : asset('/img/user.png') }}"
         class="w-8 h-8 rounded-full object-cover border-2 border-gray-300"
         alt="">

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

            @if($isOwner)
                <div class="flex gap-2 text-xs">
                    <button type="button"
                            class="btn-edit-comment text-blue-600 hover:underline">
                        Edit
                    </button>

                    <button type="button"
                            class="btn-delete-comment text-red-600 hover:underline">
                        Delete
                    </button>
                </div>
            @endif
        </div>

        {{-- normal text --}}
        <p class="comment-text mt-1">{{ $comment->content }}</p>

        {{-- inline edit form (hidden) --}}
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
