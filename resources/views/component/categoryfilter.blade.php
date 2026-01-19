<div class="flex flex-col gap-6 w-full">
    <!-- HEADER -->
    <div class="shadow-category flex justify-center items-center gap-5 bg-[#770d08] rounded-sm py-2.5">
        <span class="icon bg-white"
            style="--svg: url('https://api.iconify.design/ion/filter-outline.svg'); --size: 26px;"></span>
        <span class="text-white text-lg">Filter by Categories</span>
    </div>

    <!-- SELECTION FOR CATEGORY -->
    <div class="shadow-bg flex flex-col">
        <form action="{{ route('category.page') }}" method="GET">
            @foreach($categories as $cat)
                @php
                    // ICON MAP (change icons if you want)
                    $icons = [
                        'Announcements' => 'mdi/bullhorn',
                        'Events' => 'mdi/calendar',
                        'Discussions' => 'mdi/forum',
                        'Help' => 'mdi/help-circle',
                        'Achievements' => 'mdi/trophy',
                        'Lost & Found' => 'mdi/magnify',
                        'Marketplace' => 'mdi/store',
                        'Clubs & Organizations' => 'mdi/account-group',
                        'Entertainment' => 'mdi/movie-open',
                        'Miscellaneous' => 'mdi/dots-horizontal',
                    ];

                    $icon = $icons[$cat->category_name] ?? 'mdi/tag';
                @endphp

                <label class="flex justify-start items-center gap-7 text-sm px-6 py-5 border-b border-b-black cursor-pointer hover:bg-black/5">
                    <!-- ✅ use RADIO (1 filter only) -->
                    <input type="radio"
                        name="category_id"
                        value="{{ $cat->category_id }}"
                        class="w-4 h-4 min-w-4 min-h-4"
                        onchange="this.form.submit()"
                        {{ (isset($categoryId) && $categoryId == $cat->category_id) ? 'checked' : '' }}
                    >

                    <div class="flex justify-center items-center gap-2 text-nowrap">
                        <span class="icon bg-[#770d08]"
                            style="--svg: url('https://api.iconify.design/{{ $icon }}.svg'); --size: 20px;"></span>
                        <span>{{ $cat->category_name }}</span>
                    </div>
                </label>
            @endforeach
        </form>

        <!-- ✅ Clear filter -->
        <div class="px-6 py-4">
            <a href="{{ route('feed.page') }}"
                class="block text-center bg-gray-200 hover:bg-gray-300 text-[#545454] py-2 rounded-md text-sm">
                Clear Filter
            </a>
        </div>
    </div>
</div>
