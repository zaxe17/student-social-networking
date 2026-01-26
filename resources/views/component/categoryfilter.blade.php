<form id="categoryFilterForm" action="{{ route('category.page') }}" method="GET" class="flex flex-col gap-6 w-full">

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
    <div class="shadow-bg flex flex-col overflow-y-auto max-h-70 no-scrollbar">

        <!-- REMOVE THE BOTTOM BORDER IF THE CATEGORY IS LAST -->
        @foreach($categories as $cat)
        @php
        $iconUrl = $cat->icon_url ?? 'https://api.iconify.design/' . ($categoryIcons[$cat->category_name] ?? 'mdi:tag') . '.svg';
        $isChecked = isset($selectedCategories) && in_array($cat->category_id, $selectedCategories);
        $borderClass = $loop->last ? '' : 'border-b border-black/50';
        @endphp

        <label for="cat-{{ $cat->category_id }}" class="flex items-center gap-7 text-sm px-6 py-5 {{ $borderClass }} cursor-pointer hover:bg-black/5">

            <!-- CHECKBOX -->
            <input
                type="checkbox"
                name="category[]"
                value="{{ $cat->category_id }}"
                id="cat-{{ $cat->category_id }}"
                @if($isChecked) checked @endif>

            <!-- CATEGORY NAME AND ICON -->
            <div class="flex items-center gap-2 text-nowrap">
                <span class="icon bg-[#770d08] mt-1" style="--svg: url('{{ $iconUrl }}'); --size: 20px;"></span>
                <span>{{ $cat->category_name }}</span>
            </div>
        </label>
        @endforeach

    </div>
</form>