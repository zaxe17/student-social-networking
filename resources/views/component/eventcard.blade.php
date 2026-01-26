<div class="bg-white rounded-xl shadow-bg overflow-hidden border border-gray-100">
    <!-- EVENT IMG HEADER -->
    @if(!empty($event->header_path))
    <img src="{{ asset('storage/'.$event->header_path) }}"
        class="w-full h-40 object-cover"
        alt="header"
        onerror="this.style.display='none'">
    @else
    <div class="w-full h-40 bg-gray-100"></div>
    @endif

    @php
    $dateText = $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('m/d/Y') : 'TBA';
    $startText = $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('g:i A') : null;
    $endText = $event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('g:i A') : null;
    @endphp

    <div class="p-4">
        <!-- EVENT NAME -->
        <h3 class="text-sm font-semibold text-gray-900">{{ $event->name }}</h3>

        <div class="mt-2 text-xs text-gray-600 space-y-1">
            <!-- EVENT TIME AND DATE -->
            <div class="flex items-center gap-2">
                <span class="icon bg-gray-600 mt-0.5" style="--svg: url('https://api.iconify.design/mdi/date-range.svg'); --size: 18px;"></span>
                <span>{{ $dateText }}</span>
                @if($startText)
                <span>•</span>
                <span>{{ $startText }}@if($endText) - {{ $endText }}@endif</span>
                @endif
            </div>

            <!-- LOCATION -->
            @if(!empty($event->location))
            <div class="flex items-center gap-2">
                <span class="icon bg-gray-600 mt-0.5" style="--svg: url('https://api.iconify.design/mdi/location-on-outline.svg'); --size: 18px;"></span>
                <span class="truncate">{{ $event->location }}</span>
            </div>
            @endif
        </div>

        <!-- EVENT DESCRIPTION -->
        @if(!empty($event->description))
        <p class="mt-3 text-xs text-gray-700 line-clamp-3">{{ $event->description }}</p>
        @endif

        <!-- URL REGISTRATION FORM BUTTON -->
        @if(!empty($event->registration_url))
        <a href="{{ $event->registration_url }}" target="_blank" rel="noopener noreferrer"
            class="mt-4 inline-flex items-center justify-center text-xs bg-[#6b1d1d] text-white px-4 py-2 rounded-lg font-semibold hover:opacity-90">
            Register now
        </a>
        @endif
    </div>
</div>