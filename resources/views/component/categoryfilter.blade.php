<form id="categoryFilterForm" action="{{ route('category.page') }}" method="GET" class="flex flex-col gap-2.5 w-full">

    <!-- HEADER -->
    <div class="shadow-category flex justify-center items-center gap-5 bg-[#770d08] rounded-sm py-2.5">
        <span class="icon bg-white" style="--svg: url('https://api.iconify.design/ion/filter-outline.svg'); --size: 26px;"></span>
        <span class="text-white text-lg">Filter by Categories</span>
    </div>

    <!-- CATEGORY ICONS -->
    @php
    $categoryIcons = [
    'Announcements' => 'mdi:bullhorn',
    'Events' => 'mdi:calendar-star',
    'Discussions' => 'mdi:forum',
    'Help' => 'mdi:help-circle',
    'Achievements' => 'mdi:trophy',
    'Lost & Found' => 'mdi:magnify',
    'Marketplace' => 'mdi:store',
    'Clubs & Organizations' => 'mdi:account-group',
    'Entertainment' => 'mdi:movie-open',
    'Miscellaneous' => 'mdi:dots-horizontal',
    ];
    @endphp

    <!-- CATEGORY LIST -->
    <div class="shadow-categitems flex flex-col rounded-sm overflow-y-auto max-h-70 scrollbar-visible">

        <!-- REMOVE THE BOTTOM BORDER IF THE CATEGORY IS LAST -->
        @foreach($categories as $cat)
        @php
        $iconUrl = $cat->icon_url ?? 'https://api.iconify.design/' . ($categoryIcons[$cat->category_name] ?? 'mdi:tag') . '.svg';
        $isChecked = isset($selectedCategories) && in_array($cat->category_id, $selectedCategories);
        $borderClass = $loop->last ? '' : 'border-b border-black/40';
        @endphp

        <label for="cat-{{ $cat->category_id }}" class="group flex items-center gap-7 text-sm px-6 py-5 {{ $borderClass }} cursor-pointer transition-all duration-300 ease-in-out hover:bg-black/5 has-checked:bg-black/8 has-checked:[&>div]:translate-x-1 has-checked:[&_.icon]:bg-[#5a0906]">

            <!-- CHECKBOX -->
            <input
                type="checkbox"
                name="category[]"
                value="{{ $cat->category_id }}"
                class="hidden"
                id="cat-{{ $cat->category_id }}"
                @if($isChecked) checked @endif>

            <!-- CATEGORY NAME AND ICON -->
            <div class="flex items-center gap-2 ml-5 text-nowrap transition-all duration-300 ease-in-out group-hover:translate-x-1">
                <span class="icon bg-[#770d08] transition-all duration-300 ease-in-out group-hover:bg-[#5a0906] mt-1" style="--svg: url('{{ $iconUrl }}'); --size: 20px;"></span>
                <span>{{ $cat->category_name }}</span>
            </div>
        </label>
        @endforeach

    </div>
</form>