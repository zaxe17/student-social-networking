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