@php
$isLiked = $post->isLikedBy($student->student_id ?? null);
@endphp


<div id="commentModal" class="modal hidden">
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black/35 w-full h-screen py-8 flex justify-center items-center z-50 backdrop-blur-[1px]">

        <div class="w-2/4 h-full bg-[#F5F5F5] form-shadow rounded-3xl backdrop-blur-sm overflow-hidden flex flex-col">

            <!-- POST HEADER -->
            <div class="shadow-postheader w-full pb-3 pt-7 relative">
                <h2 class="text-center text-xl font-medium" id="modalTitle"></h2>

                <span close-modal
                    class="icon bg-black absolute top-10 right-0 -translate-x-1/2 -translate-y-1/2 transition-all duration-300 cursor-pointer"
                    style="--svg: url('https://api.iconify.design/material-symbols-light/close-rounded.svg'); --size: 35px;">
                </span>
            </div>

            <!-- POST CONTENT AND COMMENTS -->
            <div class="flex-1 overflow-y-auto">
                <div class="border-b border-b-black/50 border-solid p-8 pb-2 mb-5">

                    <!-- POST INFO -->
                    <div class="flex items-center gap-2 mb-8">
                        <img id="modalUserPhoto" src="/img/user.png" alt="" class="w-8 h-8 rounded-full object-cover cursor-pointer border-2 border-gray-300">
                        <div class="flex flex-col text-sm">
                            <span id="modalUserName">Zaxe</span>
                            <div class="flex items-center gap-3">
                                <span id="modalTimestamp" class="text-[#545454]"></span>
                                <span>•</span>
                                <span id="modalCategoryIcon" class="icon bg-[#770d08]"
                                    style="--svg: url('https://api.iconify.design/mdi/book-open-variant.svg'); --size: 18px;">
                                </span>
                                <span id="modalCategoryName" class="text-[#545454]"></span>
                            </div>
                        </div>
                    </div>

                    <!-- POST CONTENT -->
                    <p id="modalContent" class="mb-6"></p>

                    <!-- REACT AND COMMENT COUNT -->
                    <div class="flex justify-between items-center mb-3 text-sm text-[#545454]">
                        <div class="flex items-center cursor-pointer">
                            <span>❤️</span>
                            <span id="modalLikes"></span>
                        </div>
                        <span id="modalComments" class="text-[#545454] cursor-pointer hover:underline"></span>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="flex justify-around items-center mt-3 mb-5">
                        <form id="modalReactForm" action="" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 text-[#545454] cursor-pointer hover:text-black transition" aria-label="React">
                                <span id="modalHeartIcon" class="icon mt-1 bg-[#545454] transition-all duration-200"
                                    style="--svg: url('https://api.iconify.design/mdi:heart-outline.svg'); --size: 24px;"></span>
                                react
                            </button>
                        </form>
                        <button type="button" class="flex items-center gap-2 text-[#545454] cursor-pointer">
                            <span class="icon bg-[#545454] mt-1" style="--svg: url('https://api.iconify.design/mdi/comment-outline.svg'); --size: 22px;"></span>
                            comment
                        </button>
                    </div>
                </div>

                <!-- COMMENTS SECTION -->
                <div id="modalCommentsContainer">
                    @if(isset($post) && $post->comments)
                    @foreach($post->comments as $comment)
                    @include('component.comment', ['comment' => $comment])
                    @endforeach
                    @endif
                </div>
            </div>

            <!-- ADD COMMENT -->
            <div class="p-5 border-t border-black/20 bg-[#F5F5F5]">
                <form id="modalAddCommentForm" action="" method="POST" class="flex items-center gap-5 h-10">
                    @csrf
                    <img id="modalCommentUserPhoto" src="/img/user.png" alt="" class="w-10 h-10 rounded-full object-cover border-2 border-gray-300">
                    <input type="text" name="content" placeholder="write a comment..." class="w-full h-full px-2.5 rounded-lg bg-[#dde0e5] focus:outline-none placeholder:text-[#545454]">
                    <button type="submit" class="hidden"></button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const commentForm = document.getElementById('modalAddCommentForm');
        const commentsContainer = document.getElementById('modalCommentsContainer');

        commentForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const postId = commentForm.dataset.postId;
            const content = commentForm.querySelector('input[name="content"]').value;

            if (!content.trim()) return;

            try {
                const res = await fetch(`/posts/${postId}/comment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        content
                    })
                });

                const data = await res.json();

                if (data.success) {
                    commentsContainer.insertAdjacentHTML('beforeend', data.comment_html);
                    commentForm.querySelector('input[name="content"]').value = '';
                    document.getElementById('modalComments').textContent = data.comments_count + ' comment' + (data.comments_count > 1 ? 's' : '');
                }

            } catch (error) {
                console.error(error);
            }
        });
    });
</script>