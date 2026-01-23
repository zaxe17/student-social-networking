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
document.addEventListener('click', function(e) {
    const btn = e.target.closest('[target-modal="commentModal"]');
    if (!btn) return;

    const modal = document.getElementById('commentModal');
    if (!modal) return;

    const firstName = btn.getAttribute('firstname-data') || '';
    const lastName = btn.getAttribute('lastname-data') || '';
    const timestamp = btn.getAttribute('timestamp-data') || '';
    const category = btn.getAttribute('category-data') || '';
    const content = btn.getAttribute('content-data') || '';
    const likes = btn.getAttribute('likes-data') || '0';
    const commentCount = btn.getAttribute('comment-data') || '0 comment';
    const userPhoto = btn.getAttribute('userphoto-data') || '{{ asset("/img/user.png") }}';

    const categoryIcons = {
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
    const icon = categoryIcons[category] || 'mdi:tag';

    document.getElementById('modalTitle').textContent = `${firstName} ${lastName}'s post`;
    document.getElementById('modalUserName').textContent = `${firstName} ${lastName}`;
    document.getElementById('modalTimestamp').textContent = timestamp;
    document.getElementById('modalCategoryName').textContent = category;
    document.getElementById('modalCategoryIcon').style.setProperty('--svg', `url('https://api.iconify.design/${icon}.svg')`);
    document.getElementById('modalContent').innerHTML = content;
    document.getElementById('modalLikes').textContent = likes;
    document.getElementById('modalComments').textContent = `${commentCount} comment${commentCount !== 1 ? 's' : ''}`;
    document.getElementById('modalUserPhoto').src = userPhoto;
});
</script>

<!-- PROFILE EDIT MODAL -->
<script>
document.addEventListener('click', function(e) {
    const btn = e.target.closest('[target-modal="editprofModal"]');
    if (!btn) return;

    const modal = document.getElementById('editprofModal');
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

<!-- SEARCH BAR LOGIC -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const resultsContainer = document.getElementById('searchResults');

    let timeout = null;

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        clearTimeout(timeout);

        if (!query) {
            resultsContainer.classList.add('hidden');
            resultsContainer.innerHTML = '';
            return;
        }

        timeout = setTimeout(() => {
            fetch(`{{ route('search.ajax') }}?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                resultsContainer.innerHTML = '';

                // POSTS
                data.posts.forEach(post => {
                    const div = document.createElement('div');
                    div.className = "px-3 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-200";
                    div.innerHTML = `<strong>Post:</strong> ${post.content.substring(0, 50)}...`;
                    div.addEventListener('click', () => {
                        window.location.href = `/feed#post-${post.post_id}`;
                    });
                    resultsContainer.appendChild(div);
                });

                // CATEGORIES
                data.categories.forEach(cat => {
                    const div = document.createElement('div');
                    div.className = "px-3 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-200";
                    div.innerHTML = `<strong>Category:</strong> ${cat.category_name}`;
                    div.addEventListener('click', () => {
                        window.location.href = `/feed/category?category[]=${cat.category_id}`;
                    });
                    resultsContainer.appendChild(div);
                });

                // USERS / STUDENTS
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

    // REDIRECT ON CLICK FOR USERS
    document.addEventListener('click', function(e) {
        const item = e.target.closest('.search-user-item');
        if (!item) return;

        const studentId = item.getAttribute('data-student-id');
        if (!studentId) return;

        // Go to that student's profile safely
        window.location.href = `/profile/${studentId}`;
    });

    // Close dropdown if clicked outside
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
            resultsContainer.classList.add('hidden');
        }
    });
});
</script>
