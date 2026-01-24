<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @yield('app')        {{-- your main content --}}
</body>

</html>

<!-- TOGGLE OF MENU BUTTON TO SHOW SIDEBAR -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const menuBtn = document.getElementById('menuBtn');
    const menuOverlay = document.getElementById('menuOverlay');
    const menuPanel = document.getElementById('menuPanel');

    // TOGGLE MENU
    menuBtn.addEventListener('click', () => {
        if (menuOverlay.classList.contains('hidden')) {
            menuOverlay.classList.remove('hidden');
            setTimeout(() => {
                menuPanel.classList.remove('translate-x-full');
                menuPanel.classList.add('translate-x-0');
            }, 10);
        } else {
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

<!-- DROPDOWN OF 3 DOT BUTTON -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const dropdowns = document.querySelectorAll('[id^="dotDropdown-"]');
    const buttons = document.querySelectorAll('.dot-btn');

    // Toggle dropdown
    buttons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const dropdownId = btn.getAttribute('data-dropdown');
            const dropdown = document.getElementById(dropdownId);

            // Hide other dropdowns first
            dropdowns.forEach(d => {
                if (d !== dropdown) d.classList.add('hidden');
            });

            // Toggle this one
            dropdown.classList.toggle('hidden');
        });
    });

    // Click outside closes all
    document.addEventListener('click', (e) => {
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(e.target) && !Array.from(buttons).some(b => b.contains(e.target))) {
                dropdown.classList.add('hidden');
            }
        });
    });

    // Target modal items
    dropdowns.forEach(dropdown => {
        dropdown.querySelectorAll('[target-modal]').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const modalId = this.getAttribute('target-modal');
                const modal = document.getElementById(modalId);
                if (modal) modal.classList.remove('hidden');
                dropdown.classList.add('hidden');
            });
        });
    });
});
</script>

<!-- AUTO-SUBMIT CATEGORY FILTER -->
<script>
document.querySelectorAll('#categoryFilterForm input[type=checkbox]').forEach(checkbox => {
    checkbox.addEventListener('change', () => {
        document.getElementById('categoryFilterForm').submit();
    });
});
</script>

<!-- COMMENT MODAL LOGIC -->
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('[target-modal="commentModal"]');
    if (!btn) return;

    const firstName = btn.getAttribute('firstname-data') || '';
    const lastName = btn.getAttribute('lastname-data') || '';
    const timestamp = btn.getAttribute('timestamp-data') || '';
    const category = btn.getAttribute('category-data') || '';
    const content = btn.getAttribute('content-data') || '';
    const likes = btn.getAttribute('likes-data') || '0';
    const commentCount = parseInt(btn.getAttribute('comment-data') || '0', 10);
    const postId = btn.getAttribute('postid-data');
    const isLiked = btn.getAttribute('liked-data') === '1';
    const userPhoto = btn.getAttribute('userphoto-data');

    /* ---------------- COMMENTS FORM ---------------- */
    const commentForm = document.getElementById('modalAddCommentForm');
    if (commentForm) commentForm.dataset.postId = postId;

    /* ---------------- LOAD COMMENTS ---------------- */
    const commentsContainer = document.getElementById('modalCommentsContainer');
    commentsContainer.innerHTML =
        `<div class="px-8 text-sm text-[#545454]">Loading comments...</div>`;

    fetch(`/posts/${postId}/comments`, {
        headers: { 'Accept': 'application/json' }
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                commentsContainer.innerHTML = data.comment_html;
                document.getElementById('modalComments').textContent =
                    `${data.comments_count} comment${data.comments_count !== 1 ? 's' : ''}`;
            }
        });

    /* ---------------- LIKE / REACT ---------------- */
    const reactBtn = document.getElementById('modalReactBtn');
    const heartIcon = document.getElementById('modalHeartIcon');

    reactBtn.dataset.postId = postId;

    if (isLiked) {
        heartIcon.classList.remove('bg-[#545454]');
        heartIcon.classList.add('bg-red-600');
        heartIcon.style.setProperty(
            '--svg',
            `url('https://api.iconify.design/mdi/heart.svg')`
        );
    } else {
        heartIcon.classList.remove('bg-red-600');
        heartIcon.classList.add('bg-[#545454]');
        heartIcon.style.setProperty(
            '--svg',
            `url('https://api.iconify.design/mdi/heart-outline.svg')`
        );
    }

    /* ---------------- CATEGORY ICON ---------------- */
    const icons = {
        'Announcements': 'mdi:bullhorn',
        'Events': 'mdi:calendar-star',
        'Discussions': 'mdi:forum',
        'Help': 'mdi:help-circle',
        'Achievements': 'mdi:trophy',
        'Lost & Found': 'mdi:magnify',
        'Marketplace': 'mdi:store',
        'Clubs & Organizations': 'mdi:account-group',
        'Entertainment': 'mdi:movie-open',
        'Miscellaneous': 'mdi:dots-horizontal',
    };

    const icon = icons[category] || 'mdi:tag';

    /* ---------------- POPULATE MODAL ---------------- */
    document.getElementById('modalTitle').textContent = `${firstName} ${lastName}'s post`;
    document.getElementById('modalUserName').textContent = `${firstName} ${lastName}`;
    document.getElementById('modalTimestamp').textContent = timestamp;
    document.getElementById('modalCategoryName').textContent = category;
    document.getElementById('modalCategoryIcon')
        .style.setProperty('--svg', `url('https://api.iconify.design/${icon}.svg')`);
    document.getElementById('modalContent').innerHTML = content;
    document.getElementById('modalLikes').textContent = likes;
    document.getElementById('modalComments').textContent =
        `${commentCount} comment${commentCount !== 1 ? 's' : ''}`;
    document.getElementById('modalUserPhoto').src = userPhoto;
});
</script>

<!-- PROFILE EDIT MODAL -->
<script>
document.addEventListener('click', function(e) {
    const btn = e.target.closest('[target-modal="editProfileModal"]');
    if (!btn) return;

    const modal = document.getElementById('editProfileModal');
    if (!modal) return;

    const firstName = btn.getAttribute('firstname-data') || '';
    const lastName = btn.getAttribute('lastname-data') || '';
    const bio = btn.getAttribute('bio-data') || '';
    const instagram = btn.getAttribute('instagram-data') || '';
    const facebook = btn.getAttribute('facebook-data') || '';
    const linkedin = btn.getAttribute('linkedin-data') || '';
    const studentId = btn.getAttribute('studentId-data') || '';

    modal.querySelector('input[name="first_name"]').value = firstName;
    modal.querySelector('input[name="last_name"]').value = lastName;
    modal.querySelector('input[name="bio"]').value = bio;
    modal.querySelector('input[name="instagram"]').value = instagram;
    modal.querySelector('input[name="facebook"]').value = facebook;
    modal.querySelector('input[name="linkedin"]').value = linkedin;

    const studentIdInput = modal.querySelector('input[name="student_id"]');
    if (studentIdInput) studentIdInput.value = studentId;

    modal.classList.remove('hidden');
});
</script>

<!-- EDIT POST -->
<script>
document.addEventListener('click', async function(e) {
    const btn = e.target.closest('.editPostBtn');
    if (!btn) return;

    e.preventDefault();

    const postId = btn.getAttribute('data-post-id');
    const oldContent = btn.getAttribute('data-post-content') || '';
    if (!postId) return;

    const newContent = prompt("Edit your post:", oldContent);
    if (newContent === null) return;
    if (!newContent.trim()) return;

    try {
        const res = await fetch(`/feed/posts/${postId}/edit`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ content: newContent })
        });
        const data = await res.json();
        if (res.ok && data.success) window.location.reload();
        else { console.error(data); alert('Edit failed. Check console.'); }
    } catch (err) {
        console.error(err);
        alert('Error editing post. Check console.');
    }
});
</script>

<!-- REPORT MODAL -->
<script>
document.addEventListener('click', function(e) {
    const btn = e.target.closest('[target-modal="reportModal"]');
    if (!btn) return;

    const modal = document.getElementById('reportModal');
    if (modal) modal.classList.remove('hidden');

    const postId = btn.getAttribute('postid-data');
    const form = document.getElementById('reportForm');
    if (form && postId) form.action = `/feed/posts/${postId}/report`;
    else console.error('Report modal: missing postId-data or #reportForm');
});
</script>

<!-- SEARCH BAR LOGIC -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const resultsContainer = document.getElementById('searchResults');

    if (!searchInput || !resultsContainer) return;

    let timeout = null;

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        clearTimeout(timeout);
        if (!query) { resultsContainer.classList.add('hidden'); resultsContainer.innerHTML = ''; return; }

        timeout = setTimeout(() => {
            fetch(`{{ route('search.ajax') }}?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                resultsContainer.innerHTML = '';

                data.posts.forEach(post => {
                    const div = document.createElement('div');
                    div.className = "px-3 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-200";
                    div.innerHTML = `<strong>Post:</strong> ${post.content.substring(0, 50)}...`;
                    div.addEventListener('click', () => {
                        window.location.href = `/feed#post-${post.post_id}`;
                    });
                    resultsContainer.appendChild(div);
                });

                data.categories.forEach(cat => {
                    const div = document.createElement('div');
                    div.className = "px-3 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-200";
                    div.innerHTML = `<strong>Category:</strong> ${cat.category_name}`;
                    div.addEventListener('click', () => {
                        window.location.href = `/feed/category?category[]=${cat.category_id}`;
                    });
                    resultsContainer.appendChild(div);
                });

                data.students.forEach(user => {
                    const div = document.createElement('div');
                    div.className = "px-3 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-200 search-user-item";
                    div.setAttribute('data-student-id', user.student_id);
                    div.innerHTML = `<strong>User:</strong> ${user.first_name} ${user.last_name}`;
                    resultsContainer.appendChild(div);
                });

                resultsContainer.classList.remove('hidden');
            });
        }, 300);
    });

    document.addEventListener('click', function(e) {
        const item = e.target.closest('.search-user-item');
        if (!item) return;
        const studentId = item.getAttribute('data-student-id');
        if (!studentId) return;
        window.location.href = `/profile/${studentId}`;
    });

    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
            resultsContainer.classList.add('hidden');
        }
    });
});
</script>

<script src="{{ asset('js/modal.js') }}"></script>

<script>
/**
 * Sync all hearts and like counts for a given postId
 */
function syncLikes(postId, liked, likesCount) {
    // ❤️ Sync hearts in feed/postcards
    document.querySelectorAll(`.react-btn[data-post-id="${postId}"] [data-heart-icon]`)
        .forEach(icon => {
            icon.style.setProperty('--svg', `url('https://api.iconify.design/${liked ? 'mdi:heart' : 'mdi:heart-outline'}.svg')`);
            icon.classList.toggle('bg-red-600', liked);
            icon.classList.toggle('bg-[#545454]', !liked);
        });

    // 🔢 Sync like counts in feed/postcards
    document.querySelectorAll(`.like-count[data-post-id="${postId}"]`)
        .forEach(el => el.textContent = `❤️ ${likesCount}`);

    // 🪟 Sync modal
    const modalLikes = document.getElementById('modalLikes');
    const modalHeartIcon = document.getElementById('modalHeartIcon');
    const modalReactBtn = document.getElementById('modalReactBtn');

    if (modalLikes && modalReactBtn && modalReactBtn.dataset.postId == postId) {
        modalLikes.textContent = likesCount;
        modalHeartIcon.style.setProperty('--svg', `url('https://api.iconify.design/${liked ? 'mdi:heart' : 'mdi:heart-outline'}.svg')`);
        modalHeartIcon.classList.toggle('bg-red-600', liked);
        modalHeartIcon.classList.toggle('bg-[#545454]', !liked);
    }
}

/**
 * Handle like button click (both feed and modal)
 */
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.react-btn');
    if (!btn) return;

    const postId = btn.dataset.postId;
    if (!postId) return;

    fetch(`/feed/posts/${postId}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        if (!data) return;
        const { liked, likes_count } = data;
        syncLikes(postId, liked, likes_count);
    })
    .catch(console.error);
});

/**
 * Update modal heart and likes when modal opens
 */
document.querySelectorAll('[target-modal="commentModal"]').forEach(btn => {
    btn.addEventListener('click', function () {
        const postId = btn.dataset.postidData;
        const liked = parseInt(btn.dataset.likedData);
        const likesCount = parseInt(btn.dataset.likesData);

        const modalReactBtn = document.getElementById('modalReactBtn');
        const modalHeartIcon = document.getElementById('modalHeartIcon');
        const modalLikes = document.getElementById('modalLikes');

        modalReactBtn.dataset.postId = postId;
        modalHeartIcon.style.setProperty('--svg', `url('https://api.iconify.design/${liked ? 'mdi:heart' : 'mdi:heart-outline'}.svg')`);
        modalHeartIcon.classList.toggle('bg-red-600', liked);
        modalHeartIcon.classList.toggle('bg-[#545454]', !liked);
        modalLikes.textContent = likesCount;
    });
});
</script>