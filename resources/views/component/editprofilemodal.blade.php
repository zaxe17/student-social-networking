{{-- CropperJS --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

@if(isset($loggedInStudent))
<div id="editProfileModal" class="modal hidden">
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-screen py-8 flex justify-center items-center z-50 backdrop-blur-[1px]">

        <div class="w-2/5 h-3/4 bg-[#F5F5F5] form-shadow rounded-3xl backdrop-blur-sm overflow-hidden flex flex-col">

            {{-- Header --}}
            <div class="shadow-postheader w-full pb-3 pt-7 relative text-center">
                <h2 class="text-xl font-medium">Edit Profile</h2>
                <span close-modal class="icon absolute top-5 right-5 cursor-pointer text-xl">✖</span>
            </div>

            {{-- Form --}}
            <form id="profileForm" class="flex flex-col flex-1 overflow-y-auto p-8 gap-6" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="student_id" value="{{ $loggedInStudent?->student_id ?? '' }}">

                {{-- PROFILE PHOTO --}}
                <div class="flex flex-col items-center gap-3">
                    <img
                        id="photoPreview"
                        src="{{ $loggedInStudent?->photo ? asset('storage/'.$loggedInStudent->photo) : asset('/img/user.png') }}"
                        class="w-40 h-40 rounded-full object-cover border"
                        alt="Profile Preview">

                    <input type="file" id="photoInput" accept="image/*" class="hidden">

                    <button type="button" id="uploadBtn"
                        class="px-4 py-2 bg-[#770d08] text-white rounded cursor-pointer">
                        Upload / Take Photo
                    </button>
                </div>

                {{-- CROPPER --}}
                <div id="cropContainer" class="hidden flex flex-col items-center gap-3">
                    <img id="cropImage" class="max-w-full rounded">
                    <button type="button" id="cropBtn"
                        class="mt-3 w-full bg-green-600 text-white py-2 rounded">
                        Crop & Use
                    </button>
                </div>

                {{-- INPUT FIELDS --}}
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-6 flex flex-col gap-1">
                        <label for="first_name">First Name</label>
                        <input name="first_name" value="{{ $loggedInStudent?->first_name ?? '' }}" required
                            class="bg-[#000000]/10 py-1.5 px-2 rounded-lg focus:outline-none">
                    </div>
                    <div class="col-span-6 flex flex-col gap-1">
                        <label for="last_name">Last Name</label>
                        <input name="last_name" value="{{ $loggedInStudent?->last_name ?? '' }}" required
                            class="bg-[#000000]/10 py-1.5 px-2 rounded-lg focus:outline-none">
                    </div>
                    <div class="col-span-12 flex flex-col gap-1">
                        <label for="bio">Bio</label>
                        <input name="bio" value="{{ $loggedInStudent?->bio ?? '' }}"
                            class="bg-[#000000]/10 py-1.5 px-2 rounded-lg focus:outline-none">
                    </div>
                </div>

                {{-- SOCIAL LINKS --}}
                <div class="flex flex-col gap-2">
                    <label>Account links</label>

                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                            <span class="icon bg-black" style="--svg: url('https://api.iconify.design/mdi/instagram.svg'); --size: 35px;"></span>
                            <input type="text" id="instagram" name="instagram" value="{{ $loggedInStudent?->instagram ?? '' }}"
                                placeholder="https://www.instagram.com/username/"
                                class="w-full py-1.5 px-2 rounded-lg focus:outline-none">
                        </div>
                        <small id="instagramError" class="text-red-600"></small>
                    </div>

                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                            <span class="icon bg-[#0e2391]" style="--svg: url('https://api.iconify.design/mdi/facebook.svg'); --size: 35px;"></span>
                            <input type="text" id="facebook" name="facebook" value="{{ $loggedInStudent?->facebook ?? '' }}"
                                placeholder="https://www.facebook.com/example.username/"
                                class="w-full py-1.5 px-2 rounded-lg focus:outline-none">
                        </div>
                        <small id="facebookError" class="text-red-600"></small>
                    </div>

                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                            <span class="icon bg-[#0a66c2]" style="--svg: url('https://api.iconify.design/mdi/linkedin.svg'); --size: 35px;"></span>
                            <input type="text" id="linkedin" name="linkedin" value="{{ $loggedInStudent?->linkedin ?? '' }}"
                                placeholder="https://www.linkedin.com/in/username-example/"
                                class="w-full py-1.5 px-2 rounded-lg focus:outline-none">
                        </div>
                        <small id="linkedinError" class="text-red-600"></small>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full bg-[#770d08] text-white py-2 rounded text-lg cursor-pointer">
                    Save Changes
                </button>
                <p id="formMsg" class="text-center text-sm mt-2"></p>
            </form>
        </div>
    </div>
</div>
@endif

{{-- CropperJS & AJAX --}}
<script>
    let cropper = null;
    let croppedBlob = null;

    const uploadBtn = document.getElementById('uploadBtn');
    const photoInput = document.getElementById('photoInput');
    const cropContainer = document.getElementById('cropContainer');
    const cropImage = document.getElementById('cropImage');
    const photoPreview = document.getElementById('photoPreview');
    const cropBtn = document.getElementById('cropBtn');
    const formMsg = document.getElementById('formMsg');

    uploadBtn.onclick = () => photoInput.click();

    photoInput.onchange = e => {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = () => {
            cropImage.src = reader.result;
            cropContainer.classList.remove('hidden');

            cropper?.destroy();
            cropper = new Cropper(cropImage, {
                aspectRatio: 1,
                viewMode: 1,
                background: false,
            });
        };
        reader.readAsDataURL(file);
    };

    cropBtn.onclick = () => {
        cropper.getCroppedCanvas({
                width: 300,
                height: 300
            })
            .toBlob(blob => {
                croppedBlob = blob;
                photoPreview.src = URL.createObjectURL(blob);
                cropContainer.classList.add('hidden');
            });
    };

    // SOCIAL MEDIA VALIDATION
    const facebookInput = document.getElementById('facebook');
    const instagramInput = document.getElementById('instagram');
    const linkedinInput = document.getElementById('linkedin');

    const facebookError = document.getElementById('facebookError');
    const instagramError = document.getElementById('instagramError');
    const linkedinError = document.getElementById('linkedinError');

    // Regex patterns
    const regex = {
        facebook: /^(https?:\/\/)?(www\.)?facebook\.com\/[A-Za-z0-9\.]+\/?$/i,
        instagram: /^(https?:\/\/)?(www\.)?instagram\.com\/[A-Za-z0-9_\.]+\/?$/i,
        linkedin: /^(https?:\/\/)?(www\.)?linkedin\.com\/in\/[A-Za-z0-9_-]+\/?$/i,
    };

    // Validate function
    function validateInput(input, pattern, errorElement, platform) {
        const value = input.value.trim();
        if (value && !pattern.test(value)) {
            errorElement.textContent = `Please enter a valid ${platform} URL`;
        } else {
            errorElement.textContent = '';
        }
    }

    // Live validation
    facebookInput?.addEventListener('input', () => validateInput(facebookInput, regex.facebook, facebookError, 'Facebook'));
    instagramInput?.addEventListener('input', () => validateInput(instagramInput, regex.instagram, instagramError, 'Instagram'));
    linkedinInput?.addEventListener('input', () => validateInput(linkedinInput, regex.linkedin, linkedinError, 'LinkedIn'));

    // FORM SUBMISSION
    document.getElementById('profileForm')?.addEventListener('submit', async e => {
        e.preventDefault();
        formMsg.textContent = 'Saving...';
        formMsg.classList.remove('text-red-600', 'text-green-600');

        // Final validation before sending
        validateInput(facebookInput, regex.facebook, facebookError, 'Facebook');
        validateInput(instagramInput, regex.instagram, instagramError, 'Instagram');
        validateInput(linkedinInput, regex.linkedin, linkedinError, 'LinkedIn');

        if (facebookError.textContent || instagramError.textContent || linkedinError.textContent) {
            formMsg.textContent = 'Please fix social media URLs before saving.';
            formMsg.classList.add('text-red-600');
            return;
        }

        const formData = new FormData(e.target);
        if (croppedBlob) formData.append('photo', croppedBlob, 'profile.png');

        try {
            const res = await fetch(`{{ route('profile.update') }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await res.json();

            if (!res.ok) throw data;

            formMsg.textContent = data.message;
            formMsg.classList.add('text-green-600');

            setTimeout(() => location.reload(), 800);
        } catch (err) {
            formMsg.textContent = err.message || 'Validation failed';
            formMsg.classList.add('text-red-600');
        }
    });
</script>