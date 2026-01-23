{{-- CropperJS --}}
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css"
/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<div id="editprofModal" class="modal hidden">
    <div class="fixed inset-0 bg-black/40 flex justify-center items-center z-50 backdrop-blur">

        <div class="w-2/5 h-4/5 bg-[#F5F5F5] rounded-3xl overflow-hidden flex flex-col">

            <div class="py-5 shadow text-center relative">
                <h2 class="text-xl font-medium">Edit Profile</h2>
                <span close-modal class="absolute right-5 top-5 cursor-pointer text-xl">✖</span>
            </div>

            <form id="profileForm" class="flex-1 overflow-y-auto p-8 space-y-6">
                @csrf

                {{-- PROFILE PHOTO --}}
                <div class="flex flex-col items-center gap-3">
                    <img
                        id="photoPreview"
                        src="{{ $loggedInStudent->photo ? asset('storage/'.$loggedInStudent->photo) : asset('/img/user.png') }}"
                        class="w-40 h-40 rounded-full object-cover border"
                        alt="Profile Preview">

                    <input type="file" id="photoInput" accept="image/*" class="hidden">

                    <button type="button" id="uploadBtn"
                        class="px-4 py-2 bg-[#770d08] text-white rounded">
                        Upload / Take Photo
                    </button>
                </div>

                {{-- CROPPER --}}
                <div id="cropContainer" class="hidden">
                    <img id="cropImage" class="max-w-full rounded">
                    <button type="button" id="cropBtn"
                        class="mt-3 w-full bg-green-600 text-white py-2 rounded">
                        Crop & Use
                    </button>
                </div>

                {{-- INPUTS --}}
                <input name="first_name" value="{{ $loggedInStudent->first_name }}"
                    class="w-full p-2 rounded bg-gray-200" required>

                <input name="last_name" value="{{ $loggedInStudent->last_name }}"
                    class="w-full p-2 rounded bg-gray-200" required>

                <input name="bio" value="{{ $loggedInStudent->bio }}"
                    class="w-full p-2 rounded bg-gray-200">

                <input name="instagram" value="{{ $loggedInStudent->instagram }}"
                    class="w-full p-2 rounded" placeholder="Instagram URL">

                <input name="facebook" value="{{ $loggedInStudent->facebook }}"
                    class="w-full p-2 rounded" placeholder="Facebook URL">

                <input name="linkedin" value="{{ $loggedInStudent->linkedin }}"
                    class="w-full p-2 rounded" placeholder="LinkedIn URL">

                <button type="submit"
                    class="w-full bg-[#770d08] text-white py-2 rounded text-lg">
                    Save Changes
                </button>

                <p id="formMsg" class="text-center text-sm mt-2"></p>
            </form>
        </div>
    </div>
</div>

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
    cropper.getCroppedCanvas({ width: 300, height: 300 })
        .toBlob(blob => {
            croppedBlob = blob;
            photoPreview.src = URL.createObjectURL(blob);
            cropContainer.classList.add('hidden');
        });
};

// AJAX SUBMIT
document.getElementById('profileForm').addEventListener('submit', async e => {
    e.preventDefault();
    formMsg.textContent = 'Saving...';

    const formData = new FormData(e.target);
    if (croppedBlob) {
        formData.append('photo', croppedBlob, 'profile.png');
    }

    try {
        const res = await fetch(`{{ route('profile.update') }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        });

        const data = await res.json();

        if (!res.ok) {
            throw data;
        }

        formMsg.textContent = data.message;
        formMsg.classList.add('text-green-600');

        setTimeout(() => location.reload(), 800);

    } catch (err) {
        formMsg.textContent = err.message || 'Validation failed';
        formMsg.classList.add('text-red-600');
    }
});
</script>
