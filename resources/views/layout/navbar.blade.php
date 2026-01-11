<nav class="shadow-nav sticky top-0 w-full h-18 bg-white px-13 flex justify-between items-center">
    <!-- LOGO -->
    <div class="flex items-center gap-4">
        <img src="/img/logo.png" alt="pup_logo" class="w-10 h-10">
        <p class="text-2xl text-[#770d08] font-bold">ISKOnnect</p>
    </div>

    <!-- PAGE LIST ICON -->
    <div class="grid grid-cols-3 h-full gap-20">
        <!-- FEED PAGE -->
        <a href="{{ route('feed.page') }}" 
           class="group border-b-2 border-solid transition-all duration-300 w-25 h-full flex justify-center items-center {{ Route::currentRouteName() == 'feed.page' ? 'border-b-[#770d08]' : 'border-b-transparent hover:border-b-[#770d08]' }}">
            <span class="icon transition-all duration-300 {{ Route::currentRouteName() == 'feed.page' ? 'bg-[#770d08]' : 'bg-[#545454] group-hover:bg-[#770d08] ' }}" style="--svg: url('https://api.iconify.design/ion/newspaper-outline.svg'); --size: 24px; --icon-color: black;"></span>
        </a>

        <!-- PROFILE PAGE -->
        <a href="" class="group border-b-2 border-solid border-b-transparent hover:border-b-[#770d08] transition-all duration-300 w-25 h-full flex justify-center items-center">
            <span class="icon bg-[#545454] group-hover:bg-[#770d08] transition-all duration-300" style="--svg: url('https://api.iconify.design/mdi/user.svg'); --size: 28px; --icon-color: black;"></span>
        </a>

        <!-- SETTINGS PAGE -->
        <a href="" class="group border-b-2 border-solid border-b-transparent hover:border-b-[#770d08] transition-all duration-300 w-25 h-full flex justify-center items-center">
            <span class="icon bg-[#545454] group-hover:bg-[#770d08] transition-all duration-300" style="--svg: url('https://api.iconify.design/mdi/settings.svg'); --size: 28px; --icon-color: black;"></span>
        </a>
    </div>

    <!-- SEARCH BAR -->
    <div class="shadow-input w-60 h-8 px-2.5 bg-[#000000]/15 flex items-center rounded-sm">
        <input type="text" class="w-full text-sm focus:outline-none">
        <span class="icon bg-[#545454] group-hover:bg-[#770d08] transition-all duration-300" style="--svg: url('https://api.iconify.design/mdi/search.svg'); --size: 20px; --icon-color: black;"></span>
    </div>
</nav>