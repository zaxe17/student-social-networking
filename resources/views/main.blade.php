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

        if (!menuBtn || !menuOverlay || !menuPanel) return;

        menuBtn.addEventListener('click', e => {
            e.stopPropagation();
            menuOverlay.classList.toggle('hidden');
            setTimeout(() => {
                menuPanel.classList.toggle('translate-x-full');
                menuPanel.classList.toggle('translate-x-0');
            }, 10);
        });

        menuOverlay.addEventListener('click', e => {
            if (e.target === menuOverlay) {
                menuPanel.classList.add('translate-x-full');
                menuPanel.classList.remove('translate-x-0');
                setTimeout(() => menuOverlay.classList.add('hidden'), 300);
            }
        });

        document.addEventListener('keydown', e => {
            if (e.key === "Escape" && !menuOverlay.classList.contains('hidden')) {
                menuPanel.classList.add('translate-x-full');
                menuPanel.classList.remove('translate-x-0');
                setTimeout(() => menuOverlay.classList.add('hidden'), 300);
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