<div id="createEventModal" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40"
         onclick="document.getElementById('createEventModal').classList.add('hidden')"></div>

    <div class="relative mx-auto mt-24 w-[92%] max-w-xl bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-start justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Add new event</h2>
            <button type="button"
                class="text-gray-400 hover:text-gray-700"
                onclick="document.getElementById('createEventModal').classList.add('hidden')">
                ✕
            </button>
        </div>

        @if(session('success'))
            <div class="mt-4 text-sm bg-green-50 text-green-700 border border-green-200 rounded-lg p-3">
                {{ session('success') }}
            </div>
        @endif

        <form class="mt-5 space-y-4" method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data">
            @csrf

            <div>
                <label class="text-sm text-gray-700 font-medium">Event name:</label>
                <input name="name" value="{{ old('name') }}"
                       class="mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm text-gray-700 font-medium">Description:</label>
                <textarea name="description" rows="3"
                          class="mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="text-sm text-gray-700 font-medium">Date:</label>
                    <input type="date" name="event_date" value="{{ old('event_date') }}"
                           class="mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                    @error('event_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-700 font-medium">Time:</label>
                    <input type="time" name="start_time" value="{{ old('start_time') }}"
                           class="mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                    @error('start_time') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-700 font-medium">End:</label>
                    <input type="time" name="end_time" value="{{ old('end_time') }}"
                           class="mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                    @error('end_time') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-700 font-medium">Location:</label>
                <input name="location" value="{{ old('location') }}"
                       class="mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                @error('location') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm text-gray-700 font-medium">Registration Link/URL:</label>
                <input name="registration_url" value="{{ old('registration_url') }}"
                       class="mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                @error('registration_url') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between gap-3">
                <label class="text-sm text-gray-700 font-medium">Upload event header:</label>
                <input type="file" name="header" accept="image/*" class="text-sm">
                @error('header') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                class="w-full bg-[#6b1d1d] text-white py-2 rounded-lg font-semibold hover:opacity-90">
                Submit for verification
            </button>
        </form>
    </div>
</div>