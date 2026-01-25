<div class="bg-white rounded-lg shadow-bg p-4">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">Events</h3>

        <div class="flex items-center gap-2">
            <a href="{{ route('events.index') }}"
               class="text-xs text-gray-500 hover:text-gray-700 underline">
                View all
            </a>

            <button type="button"
                class="text-xs bg-[#6b1d1d] text-white px-3 py-1 rounded-md hover:opacity-90"
                onclick="document.getElementById('createEventModal')?.classList.remove('hidden')">
                Add new event
            </button>
        </div>
    </div>

    <div class="mt-4 space-y-3">
        @forelse($events as $event)
            @php
                $dateText = $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('m/d/Y') : 'TBA';
                $timeText = $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('g:i A') : null;
            @endphp

            <div class="rounded-lg overflow-hidden border border-gray-100 shadow-sm bg-white">
                @if(!empty($event->header_path))
                    <img src="{{ asset('storage/'.$event->header_path) }}"
                         class="w-full h-28 object-cover"
                         alt="event header"
                         onerror="this.style.display='none'">
                @else
                    <div class="w-full h-28 bg-gray-100"></div>
                @endif

                <div class="p-3">
                    <p class="text-sm font-semibold text-gray-800 line-clamp-2">
                        {{ $event->name }}
                    </p>

                    <div class="mt-2 text-xs text-gray-600 space-y-1">
                        <div class="flex items-center gap-2">
                            <span>📅</span>
                            <span>{{ $dateText }}</span>
                            @if($timeText)
                                <span>•</span>
                                <span>{{ $timeText }}</span>
                            @endif
                        </div>

                        @if(!empty($event->location))
                            <div class="flex items-center gap-2">
                                <span>📍</span>
                                <span class="truncate">{{ $event->location }}</span>
                            </div>
                        @endif
                    </div>

                    @if(!empty($event->registration_url))
                        <a href="{{ $event->registration_url }}" target="_blank" rel="noopener noreferrer"
                           class="mt-3 inline-flex text-xs bg-[#6b1d1d] text-white px-3 py-1 rounded-md hover:opacity-90">
                            Register now
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-xs text-gray-500">No upcoming events.</p>
        @endforelse
    </div>
</div>