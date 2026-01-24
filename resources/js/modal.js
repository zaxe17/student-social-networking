document.addEventListener('DOMContentLoaded', () => {

    document.addEventListener('click', function (e) {

        const trigger = e.target.closest('[target-modal]');
        if (!trigger) return;

        const modalId = trigger.getAttribute('target-modal');
        const modal = document.getElementById(modalId);
        if (!modal) return;

        modal.classList.remove('hidden');

        // CLOSE BUTTON
        modal.querySelectorAll('.close-modal').forEach(btn => {
            btn.onclick = () => modal.classList.add('hidden');
        });

        // CLICK OUTSIDE
        modal.onclick = (ev) => {
            if (ev.target === modal) modal.classList.add('hidden');
        };

        // LOAD REACTORS
        if (modalId === 'reactorsModal') {
            const postId = trigger.getAttribute('postid-data');
            if (postId) loadReactors(postId);
        }
    });
});
