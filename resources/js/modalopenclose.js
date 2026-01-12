// OPEN MODAL
document.addEventListener('click', function (e) {
    const openBtn = e.target.closest('[target-modal]');
    if (!openBtn) return;

    const modalId = openBtn.getAttribute('target-modal');
    const modal = document.getElementById(modalId);

    if (modal) {
        modal.classList.remove('hidden');
    }
});

// CLOSE MODAL (buttons inside modal)
document.addEventListener('click', function (e) {
    const closeBtn = e.target.closest('[close-modal]');
    if (!closeBtn) return;

    const modal = closeBtn.closest('.modal');
    if (modal) {
        modal.classList.add('hidden');
    }
});

// CLOSE MODAL WHEN CLICKING OVERLAY
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.add('hidden');
    }
});
