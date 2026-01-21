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
