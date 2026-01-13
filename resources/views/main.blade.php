<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @yield('app')

    <!-- Real-time password match validation -->
    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const errorMsg = document.getElementById('password-error');
        const form = document.querySelector('form');

        function checkPasswordMatch() {
            if (confirmPassword.value === "") {
                errorMsg.classList.add('hidden');
                return true;
            }

            if (password.value !== confirmPassword.value) {
                errorMsg.classList.remove('hidden');
                return false;
            } else {
                errorMsg.classList.add('hidden');
                return true;
            }
        }

        // Check as the user types
        confirmPassword.addEventListener('input', checkPasswordMatch);

        // Prevent form submission if passwords don't match
        form.addEventListener('submit', function(e) {
            if (!checkPasswordMatch()) {
                e.preventDefault();
                confirmPassword.focus();
            }
        });
    </script>
</body>

</html>