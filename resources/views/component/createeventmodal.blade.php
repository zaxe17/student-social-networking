<div id="createEventModal" class="@if($errors->any()) block @else hidden @endif fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40 cursor-pointer" onclick="document.getElementById('createEventModal').classList.add('hidden')"></div>

    <div class="relative mx-auto my-12 w-[92%] max-w-xl bg-white rounded-xl shadow-lg p-6 overflow-y-auto max-h-[90vh]">
        <div class="flex items-start justify-between">
            <h2 class="text-lg font-semibold text-gray-800 cursor-pointer">Add new event</h2>
            <span onclick="document.getElementById('createEventModal').classList.add('hidden')" class="icon bg-black absolute top-10 right-0 -translate-x-1/2 -translate-y-1/2 cursor-pointer transition-all duration-300" style="--svg: url('https://api.iconify.design/material-symbols-light/close-rounded.svg'); --size: 35px;">
            </span>
        </div>

        <!-- Removed previous success message -->

        <form id="createEventForm" class="mt-5 space-y-4" method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Event Name -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Event name</label>
                <input name="name" value="{{ old('name') }}" class="shadow-input mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none" required>
                @error('name') 
                <p class="text-xs text-red-600 mt-1">
                    {{ $message }}
                </p> 
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Description</label>
                <textarea name="description" rows="3" class="shadow-input mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none resize-none">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-xs text-red-600 mt-1">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Date & Time -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="text-sm text-gray-700 font-medium">Date</label>
                    <input id="event_date" type="date" name="event_date" value="{{ old('event_date') }}" class="shadow-input mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none" required>
                    @error('event_date')
                    <p class="text-xs text-red-600 mt-1">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-700 font-medium">Time</label>
                    <input id="start_time" type="time" name="start_time" value="{{ old('start_time') }}" class="shadow-input mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                    @error('start_time')
                    <p class="text-xs text-red-600 mt-1">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-700 font-medium">End</label>
                    <input id="end_time" type="time" name="end_time" value="{{ old('end_time') }}" class="shadow-input mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                    @error('end_time')
                    <p class="text-xs text-red-600 mt-1">
                        {{ $message }}
                    </p>
                    @enderror
                </div>
            </div>

            <!-- Location -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Location</label>
                <input name="location" value="{{ old('location') }}" class="shadow-input mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                @error('location')
                <p class="text-xs text-red-600 mt-1">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Registration URL -->
            <div>
                <label class="text-sm text-gray-700 font-medium">Registration Link/URL</label>
                <input name="registration_url" value="{{ old('registration_url') }}"
                    class="shadow-input mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                @error('registration_url')
                <p class="text-xs text-red-600 mt-1">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Upload Event Header -->
            <div class="flex items-center gap-3">
                <label class="cursor-pointer inline-flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-[#6b1d1d] rounded-lg hover:opacity-90">
                    <span class="icon bg-white" style="--svg: url('https://api.iconify.design/material-symbols/upload-rounded.svg'); --size: 20px;"></span>
                    Upload event header
                    <input type="file" name="header" accept="image/*" class="hidden">
                </label>

                <span id="fileName" class="text-xs text-gray-600 truncate">
                    @if(old('header_name')) {{ old('header_name') }} @endif
                </span>
            </div>
            @error('header') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror

            <!-- Submit Button -->
            <button id="submitEventBtn" type="submit"
                class="w-full bg-[#6b1d1d] text-white py-2 rounded-lg font-semibold hover:opacity-90 cursor-pointer">
                Submit
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        // Show selected file name
        const fileInput = document.querySelector('input[type="file"][name="header"]');
        const fileNameSpan = document.getElementById('fileName');
        fileInput.addEventListener('change', function() {
            fileNameSpan.textContent = this.files.length ? this.files[0].name : '';
        });

        // Preload file name after error if available
        @if(old('header_name'))
        fileNameSpan.textContent = "{{ old('header_name') }}";
        @endif

        // Show picker for modern browsers
        const dateInput = document.getElementById('event_date');
        const startInput = document.getElementById('start_time');
        const endInput = document.getElementById('end_time');
        [dateInput, startInput, endInput].forEach(input => {
            input.addEventListener('click', () => {
                if (typeof input.showPicker === 'function') input.showPicker();
            });
        });

        // Enable submit button only when required fields are filled
        const form = document.getElementById('createEventForm');
        const submitBtn = document.getElementById('submitEventBtn');
        const requiredFields = form.querySelectorAll('[required]');

        function checkFormValidity() {
            let isValid = true;
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                }
            });

            submitBtn.disabled = !isValid;
        }

        requiredFields.forEach(field => {
            field.addEventListener('input', checkFormValidity);
            field.addEventListener('change', checkFormValidity);
        });

        checkFormValidity();

        // Auto-show modal if validation errors exist
        @if($errors -> any())
        document.getElementById('createEventModal').classList.remove('hidden');
        checkFormValidity();
        @endif
    });
</script>