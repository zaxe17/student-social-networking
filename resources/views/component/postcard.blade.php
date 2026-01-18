<!-- POST BOX -->
<div class="shadow-bg px-10 pt-7 pb-4 mb-5">
    <!-- HEADER -->
    <div class="flex items-center gap-2 text-sm mb-8">
        <img src="/img/user.png" alt="" class="w-7 h-7">
        <span>Zaxe</span>
        <span>•</span>
        <span class="text-[#545454]">2h ago</span>
        <span>•</span>
        <span class="icon bg-[#770d08]" style="--svg: url('https://api.iconify.design/mdi/book-open-variant.svg'); --size: 18px; --icon-color: black;"></span>
        <span class="ml-auto text-[#545454] cursor-pointer {{ Route::currentRouteName() == 'archived.page' ? '' : 'hidden' }}" target-modal="reportModal">restore</span>
        <div class="relative ml-auto {{ Route::currentRouteName() == 'archived.page' ? 'hidden' : '' }}">
            <!-- ICON BUTTON -->
            <span id="dotBtn" class="icon bg-[#545454] cursor-pointer" style="--svg: url('https://api.iconify.design/solar/menu-dots-bold.svg'); --size: 25px;">
                </span>
                <!-- DROPDOWN MENU -->
                <div id="dotDropdown"
                class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg border border-gray-200 hidden z-50">
                
                <ul class="py-2 text-sm">
                    <li class="px-4 py-2 cursor-pointer flex items-center gap-1.5" target-modal="reportModal">
                        <span id="dotBtn" class="icon bg-[#545454] cursor-pointer" style="--svg: url('https://api.iconify.design/mdi/report.svg'); --size: 18px;"></span>
                        Report
                    </li>
                    <li class="px-4 py-2 cursor-pointer flex items-center gap-1.5">
                        <span id="dotBtn" class="icon bg-[#545454] cursor-pointer" style="--svg: url('https://api.iconify.design/mdi/edit-outline.svg'); --size: 18px;"></span>
                        Edit
                    </li>
                    <li class="px-4 py-2 cursor-pointer flex items-center gap-1.5 text-red-600">
                        <span id="dotBtn" class="icon bg-red-600 cursor-pointer" style="--svg: url('https://api.iconify.design/mdi/delete-outline.svg'); --size: 18px;"></span>
                        Delete
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- CONTENT PARAG -->
    <p class="border-b border-b-black/50 border-solid pb-5 mb-3.5">
        Hello! Anyone here who has taken Prof. Reyes for Hist 1? How is the workload? Do they require a lot of 'blue book' exams or more on research papers? Also, are they 'ghosting' during synchronous sessions? TIA!
    </p>

    <!-- REAC AND COMMENT COUNT -->
    <div class="flex justify-between items-center">
        <!-- EMOJI -->
        <div class="flex items-center cursor-pointer">
            <span>❤️</span>
            <span class="text-sm text-[#545454]">21</span>
        </div>

        <span target-modal="commentModal" class="text-[#545454] text-sm cursor-pointer hover:underline">20 comment</span>
    </div>

    <!-- ACTION BUTTON - REACT COMMENT -->
    <div class="flex justify-around items-center">
        <button type="button" class="flex items-center gap-2 text-[#545454] cursor-pointer">
            <span class="icon bg-[#545454] mt-1" style="--svg: url('https://api.iconify.design/mdi/heart-outline.svg'); --size: 22px; --icon-color: black;"></span>
            react
        </button>
        <button type="button" target-modal="commentModal" class="flex items-center gap-2 text-[#545454] cursor-pointer">
            <span class="icon bg-[#545454] mt-1" style="--svg: url('https://api.iconify.design/mdi/comment-outline.svg'); --size: 22px; --icon-color: black;"></span>
            comment
        </button>
    </div>
</div>