<div id="commentModal" class="modal hidden">
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black/35 w-full h-screen py-8 flex justify-center items-center z-50 backdrop-blur-[1px]">

        <div class="w-2/4 h-full bg-[#F5F5F5] form-shadow rounded-3xl backdrop-blur-sm overflow-hidden flex flex-col">

            <div class="shadow-postheader w-full pb-3 pt-7 relative">
                <h2 class="text-center text-xl font-medium" id="modalTitle"></h2>

                <span close-modal
                    class="icon bg-black absolute top-10 right-0 -translate-x-1/2 -translate-y-1/2 transition-all duration-300 cursor-pointer"
                    style="--svg: url('https://api.iconify.design/material-symbols-light/close-rounded.svg'); --size: 35px;">
                </span>
            </div>

            <div class="flex-1 overflow-y-auto">

                <div class="border-b border-b-black/50 border-solid p-8 pb-2 mb-5">

                    <!-- POST HEADER -->
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
                    <p id="modalContent" class="mb-6">
                        
                    </p>

                    <!-- REACT AND COMMENT COUNT -->
                    <div class="flex justify-between items-center">
                        <div class="flex items-center cursor-pointer">
                            <span>❤️</span>
                            <span id="modalLikes" class="text-sm text-[#545454]"></span>
                        </div>

                        <span id="modalComments" class="text-[#545454] text-sm cursor-pointer hover:underline">
                            
                        </span>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="flex justify-around items-center mt-3">
                        <button type="button" class="flex items-center gap-2 text-[#545454] cursor-pointer">
                            <span class="icon bg-[#545454] mt-1" style="--svg: url('https://api.iconify.design/mdi/heart-outline.svg'); --size: 22px;">
                            </span>
                            react
                        </button>
                        <button type="button" class="flex items-center gap-2 text-[#545454] cursor-pointer">
                            <span class="icon bg-[#545454] mt-1" style="--svg: url('https://api.iconify.design/mdi/comment-outline.svg'); --size: 22px;">
                            </span>
                            comment
                        </button>
                    </div>
                </div>

                @include('component.comment')

            </div>

            <div class="p-5 border-t border-black/20 bg-[#F5F5F5]">
                <form action="" method="" class="flex items-center gap-5 h-10">

                    <!-- USER PROFILE -->
                    <img id="modalCommentUserPhoto" src="/img/user.png" alt="" class="w-10 h-10">

                    <input type="text" placeholder="write a comment..." class="w-full h-full px-2.5 rounded-lg bg-[#dde0e5] focus:outline-none placeholder:text-[#545454]">
                </form>
            </div>

        </div>
    </div>
</div>