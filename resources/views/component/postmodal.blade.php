<!-- GET THE STUDENT LIKE TO REFLECT IN THIS MODAL -->
@php
$isLiked = false;
if (isset($post) && isset($student)) {
$isLiked = $post->isLikedBy($student->student_id ?? null);
}
@endphp
<div id="commentModal" class="modal hidden">
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black/35 w-full h-screen lg:py-8 py-5 flex justify-center lg:items-center items-end z-50 backdrop-blur-[1px]">
        <div class="lg:w-2/4 w-full lg:h-full h-3/4 bg-[#F5F5F5] form-shadow lg:rounded-3xl rounded-t-3xl backdrop-blur-sm overflow-hidden flex flex-col">
            <!-- POST HEADER -->
            <div class="shadow-postheader w-full pb-3 pt-7 relative">
                <h2 class="text-center text-xl font-medium" id="modalTitle"></h2>
                <span close-modal
                    class="icon bg-black absolute top-10 right-0 -translate-x-1/2 -translate-y-1/2 cursor-pointer transition-all duration-300"
                    style="--svg: url('https://api.iconify.design/material-symbols-light/close-rounded.svg'); --size: 35px;">
                </span>
            </div>
            <!-- POST CONTENT -->
            <div class="flex-1 overflow-y-auto no-scrollbar">
                <div class="border-b border-b-black/50 border-solid lg:px-8 lg:pt-5 lg:pb-0 p-5 pb-2 mb-5">
                    <!-- POST INFO -->
                    <div class="flex items-center gap-2 mb-8">
                        <img id="modalUserPhoto"
                            src="{{ isset($post->author) && $post->author->photo ? asset('/storage/'.$post->author->photo) : asset('/img/user.png') }}"
                            alt="User Photo"
                            class="w-9 h-9 rounded-full object-cover cursor-pointer border-2 border-gray-300">
                        <div class="flex flex-col text-sm">
                            <span id="modalUserName"></span>
                            <div class="flex items-center gap-2">
                                <span id="modalTimestamp" class="text-[#545454]"></span>
                                <span>•</span>
                                <div class="flex gap-1">
                                    <span id="modalCategoryIcon" class="icon bg-[#770d08] mt-0.5" style="--svg: url('https://api.iconify.design/mdi/tag.svg'); --size: 18px;"></span>
                                    <span id="modalCategoryName" class="text-[#545454]"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- POST CONTENT TEXT -->
                    <p id="modalContent" class="mb-2.5 wrap-break-word"></p>
                    <!-- REACT + COMMENT COUNT -->
                    <div class="flex justify-between items-center mb-3 text-sm text-[#545454]">
                        <!-- LIKE COUNT -->
                        <button type="button"
                            id="modalReactorsButton"
                            target-modal="reactorsModal"
                            postid-data="{{ $post->post_id ?? '' }}"
                            class="relative group flex items-center gap-1">
                            <span>❤️</span>
                            <span id="modalLikes">{{ $post->likes_count ?? 0 }}</span>
                        </button>

                        <!-- COMMENT COUNT -->
                        <span id="modalComments" class="text-[#545454]">
                            {{ $post->comments_count ?? 0 }} comment{{ ($post->comments_count ?? 0) > 1 ? 's' : '' }}
                        </span>
                    </div>
                    <!-- ACTION BUTTONS -->
                    <div class="flex justify-around items-center mt-3 mb-5">
                        <!-- REACT BUTTON -->
                        <button id="modalReactBtn"
                            class="react-btn group flex items-center gap-2 text-[#545454] lg:text-base text-sm transition-all duration-300 ease-in-out hover:text-black cursor-pointer"
                            data-post-id="{{ $post->post_id ?? '' }}">
                            <span id="modalHeartIcon"
                                class="icon mt-0.5 transition-all duration-100 ease-in-out group-active:scale-125 group-hover:bg-red-600 {{ $isLiked ? 'bg-red-600' : 'bg-[#545454]' }}"
                                data-heart-icon
                                style="--svg: url('https://api.iconify.design/{{ $isLiked ? 'mdi:heart' : 'mdi:heart-outline' }}.svg'); --size: 24px;">
                            </span>
                            React
                        </button>
                        <!-- COMMENT BUTTON -->
                        <button type="button"
                            class="flex items-center gap-2 text-[#545454] lg:text-base text-sm cursor-pointer"
                            onclick="document.querySelector('#modalAddCommentForm input[name=content]')?.focus()">
                            <span class="icon bg-[#545454] mt-0.5" style="--svg: url('https://api.iconify.design/mdi/comment-outline.svg'); --size: 22px;"></span>
                            Comment
                        </button>
                    </div>
                </div>
                <!-- COMMENTS CONTAINER -->
                <div id="modalCommentsContainer">
                    <div class="px-8 text-sm text-[#545454]">Loading comments...</div>
                </div>
            </div>
            <!-- ADD COMMENT FORM -->
            <div class="p-5 border-t border-black/20 bg-[#F5F5F5]">
                <form id="modalAddCommentForm"
                    action=""
                    method="POST"
                    class="flex items-center gap-5 h-10"
                    data-post-id="{{ $post->post_id ?? '' }}">
                    @csrf

                    <!-- STUDENT PROFFILE -->
                    <img id="modalCommentUserPhoto"
                        src="{{ isset($loggedInStudent) && $loggedInStudent->photo ? asset('/storage/'.$loggedInStudent->photo) : asset('/img/user.png') }}"
                        alt="User Photo"
                        class="w-10 h-10 rounded-full object-cover border-2 border-gray-300">

                    <!-- COMMENT FIELD -->
                    <input type="text"
                        name="content"
                        placeholder="write a comment..."
                        class="w-full h-full px-2.5 rounded-lg bg-[#dde0e5] focus:outline-none placeholder:text-[#545454]"
                        required>

                    <!-- SUBMIT BUTTON -->
                    <button type="submit"
                        class="flex items-center justify-center rounded-lg bg-transparent"
                        aria-label="Send comment">
                        <span class="icon bg-[#770d08] hover:bg-[#5f0a06] transition-all duration-300 ease-in-out"
                            style="--svg: url('https://api.iconify.design/material-symbols:send-rounded.svg'); --size: 30px;">
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>