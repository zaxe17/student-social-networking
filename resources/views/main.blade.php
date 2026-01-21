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

<!-- <script>
    // OPEN COMMENT MODAL
    function openCommentModal(postId) {
        // Get the modal element
        const modal = document.getElementById('commentModal');

        // Fetch post content dynamically (optional, you could do this via an API call or pass post data from server-side)
        const postTitle = document.getElementById('postTitle');
        const postContent = document.getElementById('postContent');

        // Assuming you can access post content from JS or pass it as data attributes
        const postData = window.postData[postId]; // Replace with how you pass the post data

        if (postData) {
            postTitle.textContent = postData.title;
            postContent.innerHTML = postData.content;
        }

        // Show modal
        modal.classList.remove('hidden');
    }

    // CLOSE MODAL (on close button click)
    document.addEventListener('click', function(e) {
        const closeBtn = e.target.closest('[close-modal]');
        if (!closeBtn) return;

        const modal = closeBtn.closest('.modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    });

    // CLOSE MODAL WHEN CLICKING OUTSIDE
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.classList.add('hidden');
        }
    });
</script>
 -->

<!-- AUTO-SUBMIT JS -->
<script>
    document.querySelectorAll('#categoryFilterForm input[type=checkbox]').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            document.getElementById('categoryFilterForm').submit();
        });
    });
</script>

<!-- WHEN I CLICK THE COMMMENT BUTTON DISPLAY THE DATA IN POSTMODAL BLADE -->
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
        const commentCount = parseInt(btn.getAttribute('comment-data') || '0', 10);
        const postId = btn.getAttribute('postid-data') || '';
        const userPhoto = btn.getAttribute('userphoto-data') || '/img/user.png'; // optional if you want dynamic photo

        // Set the post id so the modal comment form knows where to submit
        const commentForm = document.getElementById('modalAddCommentForm');
        if (commentForm) {
            commentForm.dataset.postId = postId;
        }

        const commentsContainer = document.getElementById('modalCommentsContainer');
        if (commentsContainer) {
            commentsContainer.innerHTML = `<div class="px-8 text-sm text-[#545454]">Loading comments...</div>`;
        }

        // ✅ FIXED URL: use /posts/{id}/comments because that’s the route we added
        if (commentsContainer && postId) {
            fetch(`/posts/${postId}/comments`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    commentsContainer.innerHTML = data.comment_html || '';
                    document.getElementById('modalComments').textContent =
                        `${data.comments_count} comment${data.comments_count !== 1 ? 's' : ''}`;
                } else {
                    commentsContainer.innerHTML = '';
                }
            })
            .catch(err => {
                console.error(err);
                commentsContainer.innerHTML = '';
            });
        }

        // Set the like action inside the modal
        const reactForm = document.getElementById('modalReactForm');
        if (reactForm && postId) {
            reactForm.action = `/feed/posts/${postId}/like`;
        }

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

        document.getElementById('modalTitle').textContent = `${firstName} ${lastName}\'s post`;
        document.getElementById('modalUserName').textContent = `${firstName} ${lastName}`;
        document.getElementById('modalTimestamp').textContent = timestamp;
        document.getElementById('modalCategoryName').textContent = category;
        document.getElementById('modalCategoryIcon').style.setProperty('--svg', `url('https://api.iconify.design/${icon}.svg')`);
        document.getElementById('modalContent').innerHTML = (content || '').replace(/\n/g, '<br>');
        document.getElementById('modalLikes').textContent = likes;
        document.getElementById('modalComments').textContent = `${commentCount} comment${commentCount !== 1 ? 's' : ''}`;

        document.getElementById('modalUserPhoto').src = userPhoto;
    });
</script>

<!-- PROFILE EDIT MODAL -->
<script>
    document.addEventListener('click', function(e) {
        // Hanapin yung button / li na may target-modal
        const btn = e.target.closest('[target-modal="editprofModal"]');
        if (!btn) return;

        // Hanapin yung modal
        const modal = document.getElementById('editprofModal');
        if (!modal) return;

        // Kunin yung data attributes
        const firstName = btn.getAttribute('firstname-data') || '';
        const lastName = btn.getAttribute('lastname-data') || '';
        const bio = btn.getAttribute('bio-data') || '';
        const instagram = btn.getAttribute('instagram-data') || '';
        const facebook = btn.getAttribute('facebook-data') || '';
        const linkedin = btn.getAttribute('linkedin-data') || '';
        const studentId = btn.getAttribute('studentId-data') || '';

        // I-fill ang inputs sa modal
        modal.querySelector('input[name="first_name"]').value = firstName;
        modal.querySelector('input[name="last_name"]').value = lastName;
        modal.querySelector('input[name="bio"]').value = bio;
        modal.querySelector('input[name="instagram"]').value = instagram;
        modal.querySelector('input[name="facebook"]').value = facebook;
        modal.querySelector('input[name="linkedin"]').value = linkedin;

        // Optional: kung meron kang hidden input para sa student id
        const studentIdInput = modal.querySelector('input[name="student_id"]');
        if (studentIdInput) studentIdInput.value = studentId;

        // I-show yung modal
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

            if (res.ok && data.success) {
                window.location.reload();
            } else {
                console.error(data);
                alert('Edit failed. Check console.');
            }
        } catch (err) {
            console.error(err);
            alert('Error editing post. Check console.');
        }
    });
</script>

<script>
document.addEventListener('click', function(e) {
    const btn = e.target.closest('[target-modal="reportModal"]');
    if (!btn) return;

    const modal = document.getElementById('reportModal');
    if (modal) modal.classList.remove('hidden');

    const postId = btn.getAttribute('postid-data');
    const form = document.getElementById('reportForm');

    if (form && postId) {
        form.action = `/feed/posts/${postId}/report`;
    } else {
        console.error('Report modal: missing postId-data or #reportForm');
    }
});
</script>
