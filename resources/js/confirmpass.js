const password = document.getElementById('password');
const confirmPassword = document.getElementById('password_confirmation');
const errorText = document.getElementById('password-error');

function checkPasswordMatch() {
    if (confirmPassword.value === '') {
        errorText.classList.add('hidden');
        return;
    }

    if (password.value !== confirmPassword.value) {
        errorText.classList.remove('hidden');
    } else {
        errorText.classList.add('hidden');
    }
}

password.addEventListener('input', checkPasswordMatch);
confirmPassword.addEventListener('input', checkPasswordMatch);