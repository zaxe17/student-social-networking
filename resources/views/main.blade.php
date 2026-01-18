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
</body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const menuBtn = document.getElementById('menuBtn');
        const menuOverlay = document.getElementById('menuOverlay');
        const menuPanel = document.getElementById('menuPanel');

        // TOGGLE MENU
        menuBtn.addEventListener('click', () => {
            if (menuOverlay.classList.contains('hidden')) {
                // OPEN
                menuOverlay.classList.remove('hidden');
                setTimeout(() => {
                    menuPanel.classList.remove('translate-x-full');
                    menuPanel.classList.add('translate-x-0');
                }, 10);
            } else {
                // CLOSE
                menuPanel.classList.remove('translate-x-0');
                menuPanel.classList.add('translate-x-full');
                setTimeout(() => {
                    menuOverlay.classList.add('hidden');
                }, 300);
            }
        });

        // CLOSE MENU when clicking outside panel
        menuOverlay.addEventListener('click', (e) => {
            if (e.target === menuOverlay) {
                menuPanel.classList.remove('translate-x-0');
                menuPanel.classList.add('translate-x-full');
                setTimeout(() => {
                    menuOverlay.classList.add('hidden');
                }, 300);
            }
        });

        // CLOSE with ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === "Escape" && !menuOverlay.classList.contains('hidden')) {
                menuPanel.classList.remove('translate-x-0');
                menuPanel.classList.add('translate-x-full');
                setTimeout(() => {
                    menuOverlay.classList.add('hidden');
                }, 300);
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const btn = document.getElementById('dotBtn');
        const dropdown = document.getElementById('dotDropdown');

        // TOGGLE DROPDOWN
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });

        // CLICK OUTSIDE = CLOSE
        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target) && e.target !== btn) {
                dropdown.classList.add('hidden');
            }
        });

        // PARA SA target-modal ITEMS
        dropdown.querySelectorAll('[target-modal]').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                const modalId = this.getAttribute('target-modal');
                const modal = document.getElementById(modalId);

                if (modal) {
                    modal.classList.remove('hidden');
                }

                dropdown.classList.add('hidden');
            });
        });

    });
</script>