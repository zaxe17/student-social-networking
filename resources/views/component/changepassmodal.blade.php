<div id="changepassModal" class="modal hidden">
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black/35 w-full h-screen flex justify-center items-center z-50 backdrop-blur-[1px]">
        <div class="w-2/6 bg-[#F5F5F5] form-shadow rounded-3xl backdrop-blur-sm overflow-hidden">

            <!-- HEADER OF MODAL -->
            <div class="shadow-postheader w-full pb-3 pt-7 relative">
                <!-- TITLE -->
                <h2 class="text-center text-xl font-medium">{{ $title }}</h2>

                <!-- CLOSE BUTTON -->
                <span close-modal class="icon bg-black absolute top-10 right-0 -translate-x-1/2 -translate-y-1/2 transition-all duration-300 cursor-pointer" style="--svg: url('https://api.iconify.design/material-symbols-light/close-rounded.svg'); --size: 35px; --icon-color: black;"></span>
            </div>

            <!-- CHANGE PASSWORD FORM FIELDS -->
            <form id="changepassForm" action="{{ route('student.changePassword') }}" method="POST" class="flex flex-col gap-3.5 p-8">
                @csrf

                <!-- OLD PASSWORD FIELD -->
                <div class="flex flex-col gap-2">
                    <label for="current_password">Old Password</label>
                    <input type="password" name="current_password" id="current_password" class="shadow-input p-2 bg-[#dde0e5] focus:outline-none" required>
                    <span id="oldPasswordError" class="text-red-600 text-sm hidden">Old password is incorrect.</span>
                </div>
                
                <!-- NEW PASSWORD FIELD -->
                <div class="flex flex-col gap-2">
                    <label for="new_password">New Password</label>
                    <input type="password" name="new_password" id="new_password" class="shadow-input p-2 focus:outline-none" required>
                    <!-- Real-time weak password message -->
                    <span id="passwordWeak" class="text-red-600 text-sm hidden">Password is too weak (minimum should 6 characters).</span>
                </div>
                
                <!-- CONFIRM PASSWORD FIELD -->
                <div class="flex flex-col gap-2">
                    <label for="new_password_confirmation">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="shadow-input p-2 focus:outline-none" required>
                    <span id="passwordMismatch" class="text-red-600 text-sm hidden">Passwords do not match.</span>
                </div>
                
                <!-- SAVE BUTTON -->
                <div class="flex justify-center mt-7">
                    <button type="submit" id="saveBtn"
                        class="w-30 flex justify-center items-center py-1.5 text-xl font-medium
                        text-white bg-[#770d08] rounded-md
                        cursor-pointer hover:opacity-90 transition
                        disabled:opacity-50 disabled:cursor-not-allowed">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPT FOR CONFIRMING PASSWORD IF NOT MATCH -->
<script>
    const form = document.getElementById('changepassForm');
    const oldPassword = document.getElementById('current_password');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');
    const saveBtn = document.getElementById('saveBtn');

    const oldPasswordError = document.getElementById('oldPasswordError');
    const mismatchMsg = document.getElementById('passwordMismatch');
    const passwordWeak = document.getElementById('passwordWeak');

    let oldPasswordValid = false;

    function toggleSave() {
        // Enable Save only if old password is valid, new passwords match, and new password >=6
        const weak = newPassword.value.length < 6;
        saveBtn.disabled = !oldPasswordValid || newPassword.value !== confirmPassword.value || weak;
    }

    function validatePasswords() {
        // Check password match
        if (confirmPassword.value === "") {
            mismatchMsg.classList.add('hidden');
        } else if (newPassword.value !== confirmPassword.value) {
            mismatchMsg.classList.remove('hidden');
        } else {
            mismatchMsg.classList.add('hidden');
        }

        // Check password length
        if (newPassword.value.length > 0 && newPassword.value.length < 6) {
            passwordWeak.classList.remove('hidden');
        } else {
            passwordWeak.classList.add('hidden');
        }

        toggleSave();
    }

    // Real-time old password validation
    oldPassword.addEventListener('input', function() {
        const value = oldPassword.value;
        if (value.length === 0) {
            oldPasswordError.classList.add('hidden');
            oldPasswordValid = false;
            toggleSave();
            return;
        }

        fetch('{{ route("student.validateOldPassword") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    current_password: value
                })
            })
            .then(res => {
                // Check if user is unauthorized (session expired)
                if (res.status === 401) {
                    alert('Session expired. Please log in again.');
                    window.location.href = '{{ route("auth.page") }}'; // redirect to login
                    throw new Error('Session expired');
                }
                return res.json();
            })
            .then(data => {
                if (data.valid) {
                    oldPasswordError.classList.add('hidden');
                    oldPasswordValid = true;
                } else {
                    oldPasswordError.classList.remove('hidden');
                    oldPasswordValid = false;
                }
                toggleSave();
            })
            .catch(err => {
                console.log(err);
            });
    });

    // Validate new password & confirmation in real-time
    newPassword.addEventListener('input', validatePasswords);
    confirmPassword.addEventListener('input', validatePasswords);

    // Prevent submission if invalid
    form.addEventListener('submit', function(e) {
        if (!oldPasswordValid || newPassword.value !== confirmPassword.value || newPassword.value.length < 6) {
            e.preventDefault();
        }
    });
</script>