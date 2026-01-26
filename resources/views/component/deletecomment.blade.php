<div id="deletecomment" class="modal hidden">
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black/35 w-full h-screen flex justify-center items-center z-60 backdrop-blur-[1px]">
        <div class="w-2/6 bg-[#F5F5F5] form-shadow rounded-3xl backdrop-blur-sm overflow-hidden">

            <!-- HEADER -->
            <div class="shadow-postheader w-full pb-3 pt-7 relative">
                <h2 class="text-center text-xl font-medium">Delete comment</h2>

                <!-- CLOSE BUTTON -->
                <span close-modal
                    class="icon bg-black absolute top-10 right-0 -translate-x-1/2 -translate-y-1/2 cursor-pointer"
                    style="--svg: url('https://api.iconify.design/material-symbols-light/close-rounded.svg'); --size: 35px;">
                </span>
            </div>

            <!-- ACTION BUTTON -->
            <div class="flex flex-col gap-3.5 p-8">
                <!-- CANCEL BUTTON -->
                <button type="button" close-modal class="w-full py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
                    Cancel
                </button>

                <!-- DELETE BUTTON -->
                <button type="button" id="confirmDeleteComment" class="w-full py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>