document.addEventListener('DOMContentLoaded', () => {
    const signinForm = document.getElementById('signin-form');
    const signupForm = document.getElementById('signup-form');

    document.getElementById('show-signup').addEventListener('click', () => {
        signinForm.classList.add('hidden');
        signupForm.classList.remove('hidden');
    });

    document.getElementById('show-signin').addEventListener('click', () => {
        signupForm.classList.add('hidden');
        signinForm.classList.remove('hidden');
    });
});