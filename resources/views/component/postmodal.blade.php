@php
$isLiked = false;
if (isset($post) && isset($student)) {
    $isLiked = $post->isLikedBy($student->student_id ?? null);
}
@endphp
<div id="commentModal" class="modal hidden">
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black/35 w-full h-screen py-8 flex justify-center items-center z-50 backdrop-blur-[1px]">
        <div class="w-2/4 h-full bg-[#F5F5F5] form-shadow rounded-3xl backdrop-blur-sm overflow-hidden flex flex-col">
            {{-- POST HEADER --}}
            <div class="shadow-postheader w-full pb-3 pt-7 relative">
                <h2 class="text-center text-xl font-medium" id="modalTitle"></h2>
                <span close-modal
                      class="icon bg-black absolute top-10 right-0 -translate-x-1/2 -translate-y-1/2 cursor-pointer transition-all duration-300"
                      style="--svg: url('https://api.iconify.design/material-symbols-light/close-rounded.svg'); --size: 35px;">
                </span>
            </div>
            {{-- POST CONTENT --}}
            <div class="flex-1 overflow-y-auto">
                <div class="border-b border-b-black/50 border-solid p-8 pb-2 mb-5">
                    {{-- POST INFO --}}
                    <div class="flex items-center gap-2 mb-8">
                        <img id="modalUserPhoto"
                             src="{{ isset($post->author) && $post->author->photo ? asset('storage/'.$post->author->photo) : asset('/img/user.png') }}"
                             alt="User Photo"
                             class="w-8 h-8 rounded-full object-cover cursor-pointer border-2 border-gray-300">
                        <div class="flex flex-col text-sm">
                            <span id="modalUserName"></span>
                            <div class="flex items-center gap-3">
                                <span id="modalTimestamp" class="text-[#545454]"></span>
                                <span>•</span>
                                <span id="modalCategoryIcon" class="icon bg-[#770d08]" style="--svg: url('https://api.iconify.design/mdi/tag.svg'); --size: 18px;"></span>
                                <span id="modalCategoryName" class="text-[#545454]"></span>
                            </div>
                        </div>
                    </div>
                    {{-- POST CONTENT TEXT --}}
                    <p id="modalContent" class="mb-6"></p>
                    {{-- REACT + COMMENT COUNT --}}
                    <div class="flex justify-between items-center mb-3 text-sm text-[#545454]">
                        {{-- LIKE COUNT WITH HOVER PREVIEW --}}
                        <button type="button" 
                                id="modalReactorsButton"
                                target-modal="reactorsModal" 
                                postid-data="{{ $post->post_id ?? '' }}"
                                class="relative group flex items-center gap-1 cursor-pointer">
                            <span>❤️</span>
                            <span id="modalLikes">{{ $post->likes_count ?? 0 }}</span>
                            {{-- Hover preview will be inserted here dynamically --}}
                        </button>
                        
                        <span id="modalComments" class="text-[#545454] cursor-pointer hover:underline">
                            {{ $post->comments_count ?? 0 }} comment{{ ($post->comments_count ?? 0) > 1 ? 's' : '' }}
                        </span>
                    </div>
                    {{-- ACTION BUTTONS --}}
                    <div class="flex justify-around items-center mt-3 mb-5">
                        <button id="modalReactBtn"
                                class="react-btn flex items-center gap-2 text-[#545454] hover:text-black transition"
                                data-post-id="{{ $post->post_id ?? '' }}">
                            <span id="modalHeartIcon"
                                  class="icon mt-1 {{ $isLiked ? 'bg-red-600' : 'bg-[#545454]' }}"
                                  data-heart-icon
                                  style="--svg: url('https://api.iconify.design/{{ $isLiked ? 'mdi:heart' : 'mdi:heart-outline' }}.svg'); --size: 24px;">
                            </span>
                            React
                        </button>
                        <button type="button"
                                class="flex items-center gap-2 text-[#545454] cursor-pointer"
                                onclick="document.querySelector('#modalAddCommentForm input[name=content]')?.focus()">
                            <span class="icon bg-[#545454] mt-1" style="--svg: url('https://api.iconify.design/mdi/comment-outline.svg'); --size: 22px;"></span>
                            Comment
                        </button>
                    </div>
                </div>
                {{-- COMMENTS CONTAINER --}}
                <div id="modalCommentsContainer">
                    <div class="px-8 text-sm text-[#545454]">Loading comments...</div>
                </div>
            </div>
            {{-- ADD COMMENT --}}
            <div class="p-5 border-t border-black/20 bg-[#F5F5F5]">
                <form id="modalAddCommentForm"
                      action=""
                      method="POST"
                      class="flex items-center gap-5 h-10"
                      data-post-id="{{ $post->post_id ?? '' }}">
                    @csrf
                    <img id="modalCommentUserPhoto"
                         src="{{ isset($loggedInStudent) && $loggedInStudent->photo ? asset('storage/'.$loggedInStudent->photo) : asset('/img/user.png') }}"
                         alt="User Photo"
                         class="w-10 h-10 rounded-full object-cover border-2 border-gray-300">
                    <input type="text"
                           name="content"
                           placeholder="write a comment..."
                           class="w-full h-full px-2.5 rounded-lg bg-[#dde0e5] focus:outline-none placeholder:text-[#545454]"
                           required>
                    <button type="submit"
                            class="flex items-center justify-center w-10 h-10 rounded-lg bg-[#770d08] text-white hover:bg-[#5f0a06] transition"
                            aria-label="Send comment">
                        <span class="icon bg-white"
                              style="--svg: url('https://api.iconify.design/material-symbols:send-rounded.svg'); --size: 20px;">
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>