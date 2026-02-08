<div class="flex justify-between mb-8 h-10 text-[#36384e]">
    <div class="flex flex-col">
        <!-- FILTER HEADER -->
        <span class="font-light">Filter by Categories ></span>

        <!-- SHOW CATEGORY NAME WHEN CHECKBOX IS SELECTED -->
        @php
        $selectedCategoryIds = request()->query('category', []);

        $selectedCategoryNames = collect($categories)
        ->whereIn('category_id', $selectedCategoryIds)
        ->pluck('category_name')
        ->all();
        @endphp

        <!-- DISPLAY SELECTED CATEGORY NAME -->
        @if(count($selectedCategoryNames))
        <p class="font-medium">{{ implode(', ', $selectedCategoryNames) }}</p>
        @else
        <p class="font-medium text-gray-500">All Categories</p>
        @endif
    </div>

    <span class="filter lg:hidden inline-block bg-black" style="--svg: url('https://api.iconify.design/ion/filter-outline.svg'); --size: 26px;"></span>
</div>