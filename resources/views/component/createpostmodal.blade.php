<div id="createPostModal" class="modal hidden">
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black/35 w-full h-screen flex justify-center items-center z-50 backdrop-blur-[1px]">
        <div class="w-2/5 bg-[#F5F5F5] form-shadow rounded-3xl backdrop-blur-sm overflow-hidden">

            <!-- HEADER OF MODAL -->
            <div class="shadow-postheader w-full pb-3 pt-7 relative">
                <!-- Title -->
                <h2 class="text-center text-xl font-medium">{{ $title }}</h2>

                <!-- Close Button -->
                <span close-modal class="icon bg-black absolute top-10 right-0 -translate-x-1/2 -translate-y-1/2 transition-all duration-300 cursor-pointer" style="--svg: url('https://api.iconify.design/material-symbols-light/close-rounded.svg'); --size: 35px; --icon-color: black;"></span>
            </div>

            <form action="" method="" class="flex flex-col gap-3.5 p-8">
                <!-- USER DISPLAY -->
                <div class="flex items-center gap-5">
                    <img src="/img/user.png" alt="" class="w-10 h-10">
                    <span class="text-xl">Jan Marc Soberano Jacolbia</span>
                </div>

                <!-- COMMENT / POST -->
                <textarea name="" id="" placeholder="Type something..." class="w-full h-25 px-3 focus:outline-none resize-none"></textarea>

                <select class="w-full bg-black/15 px-4 py-2 rounded-md border-none focus:outline-none" aria-label="">
                    <option selected>Select category</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>

                <div class="flex justify-center">
                    <button type="submit" class="w-30 flex justify-center items-center py-1.5 text-xl font-medium text-white bg-[#770d08] rounded-md">Post</button>
                </div>
            </form>
        </div>
    </div>
</div>