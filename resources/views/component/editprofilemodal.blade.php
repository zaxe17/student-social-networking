<div id="editprofModal" class="modal hidden">
    <div class="fixed inset-0 bg-black/40 flex justify-center items-center z-50">

        <div class="w-2/5 h-[80%] bg-[#F5F5F5] rounded-3xl overflow-hidden flex flex-col">

            <!-- HEADER -->
            <div class="border-b px-6 py-4 relative">
                <h2 class="text-center text-xl font-medium">Edit Profile</h2>
                <span close-modal
                      class="absolute right-6 top-4 cursor-pointer text-xl">✕</span>
            </div>

            <!-- FORM -->
            <form action="{{ route('profile.update') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  onsubmit="return confirm('Save changes to your profile?')"
                  class="flex-1 overflow-y-auto p-6 space-y-6">
                @csrf

                <!-- PROFILE PHOTO -->
                <div class="flex flex-col items-center gap-3">
                    <img
                        src="{{ auth()->user()->photo
                                ? asset('storage/' . auth()->user()->photo)
                                : asset('/img/user.png') }}"
                        class="w-36 h-36 rounded-full object-cover"
                    >

                    <label class="cursor-pointer bg-[#770d08] text-white px-4 py-2 rounded-md">
                        Upload Photo
                        <input type="file" name="photo" accept="image/*" hidden>
                    </label>

                    @error('photo')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NAMES -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label>First Name</label>
                        <input type="text" name="first_name"
                               value="{{ old('first_name', auth()->user()->first_name) }}"
                               class="w-full bg-gray-200 p-2 rounded" required>
                    </div>

                    <div>
                        <label>Last Name</label>
                        <input type="text" name="last_name"
                               value="{{ old('last_name', auth()->user()->last_name) }}"
                               class="w-full bg-gray-200 p-2 rounded" required>
                    </div>
                </div>

                <!-- BIO -->
                <div>
                    <label>Bio</label>
                    <textarea name="bio"
                              class="w-full bg-gray-200 p-2 rounded"
                              rows="2">{{ old('bio', auth()->user()->bio) }}</textarea>
                </div>

                <!-- LINKS -->
                <div class="space-y-3">
                    <input type="text" name="instagram"
                           value="{{ old('instagram', auth()->user()->instagram) }}"
                           placeholder="Instagram link"
                           class="w-full bg-gray-200 p-2 rounded">

                    <input type="text" name="facebook"
                           value="{{ old('facebook', auth()->user()->facebook) }}"
                           placeholder="Facebook link"
                           class="w-full bg-gray-200 p-2 rounded">

                    <input type="text" name="linkedin"
                           value="{{ old('linkedin', auth()->user()->linkedin) }}"
                           placeholder="LinkedIn link"
                           class="w-full bg-gray-200 p-2 rounded">
                </div>

                <!-- SAVE -->
                <div class="flex justify-center pt-4">
                    <button type="submit"
                        class="bg-[#770d08] hover:bg-[#5e0a06] text-white px-8 py-2 rounded-md">
                        Save
                    </button>
                </div>

                @if(session('success'))
                    <p class="text-green-700 text-center mt-2">
                        {{ session('success') }}
                    </p>
                @endif
            </form>
        </div>
    </div>
</div>
