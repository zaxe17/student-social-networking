<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Document')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @yield('app')

    <!-- ========================= -->
    <!-- TOGGLE MENU SIDEBAR -->
    <!-- ========================= -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const menuBtn = document.getElementById('menuBtn');
            const menuOverlay = document.getElementById('menuOverlay');
            const menuPanel = document.getElementById('menuPanel');

            if (!menuBtn || !menuOverlay || !menuPanel) return;

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
                    setTimeout(() => menuOverlay.classList.add('hidden'), 300);
                }
            });

            menuOverlay.addEventListener('click', (e) => {
                if (e.target === menuOverlay) {
                    menuPanel.classList.remove('translate-x-0');
                    menuPanel.classList.add('translate-x-full');
                    setTimeout(() => menuOverlay.classList.add('hidden'), 300);
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === "Escape" && !menuOverlay.classList.contains('hidden')) {
                    menuPanel.classList.remove('translate-x-0');
                    menuPanel.classList.add('translate-x-full');
                    setTimeout(() => menuOverlay.classList.add('hidden'), 300);
                }
            });
        });
    </script>

    <!-- ========================= -->
    <!-- 3 DOT DROPDOWN FOR POST CARD-->
    <!-- ========================= -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropdowns = document.querySelectorAll('[id^="dotDropdown-"]');
            const buttons = document.querySelectorAll('.dot-btn');

            if (!dropdowns.length || !buttons.length) return;

            buttons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const dropdownId = btn.getAttribute('data-dropdown');
                    const dropdown = document.getElementById(dropdownId);
                    if (!dropdown) return;

                    dropdowns.forEach(d => {
                        if (d !== dropdown) d.classList.add('hidden');
                    });
                    dropdown.classList.toggle('hidden');
                });
            });

            document.addEventListener('click', (e) => {
                dropdowns.forEach(dropdown => {
                    if (!dropdown.contains(e.target) && !Array.from(buttons).some(b => b.contains(e.target))) {
                        dropdown.classList.add('hidden');
                    }
                });
            });

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

    <!-- ========================= -->
    <!-- AUTO SUBMIT CATEGORY FILTER -->
    <!-- ========================= -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('categoryFilterForm');
            if (!form) return;

            form.querySelectorAll('input[type=checkbox]').forEach(checkbox => {
                checkbox.addEventListener('change', () => form.submit());
            });
        });
    </script>

    <!-- ========================= -->
    <!-- COMMENT MODAL OPEN + LOAD -->
    <!-- ========================= -->
    <script>
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('[target-modal="commentModal"]');
            if (!btn) return;
            const postId = btn.getAttribute('postid-data');
            if (!postId) {
                console.error('Missing postid-data');
                return;
            }
            // Open modal
            const modal = document.getElementById('commentModal');
            if (modal) modal.classList.remove('hidden');

            // Get all data attributes
            const firstName = btn.getAttribute('firstname-data') || '';
            const lastName = btn.getAttribute('lastname-data') || '';
            const timestamp = btn.getAttribute('timestamp-data') || '';
            const category = btn.getAttribute('category-data') || '';
            const content = btn.getAttribute('content-data') || '';
            const likes = btn.getAttribute('likes-data') || '0';
            const commentCount = parseInt(btn.getAttribute('comment-data') || '0', 10);
            const isLiked = btn.getAttribute('liked-data') === '1';
            const userPhoto = btn.getAttribute('userphoto-data') || "{{ asset('/img/user.png') }}";

            // Populate modal text elements
            const titleEl = document.getElementById('modalTitle');
            const userEl = document.getElementById('modalUserName');
            const timeEl = document.getElementById('modalTimestamp');
            const catEl = document.getElementById('modalCategoryName');
            const contentEl = document.getElementById('modalContent');
            const likesEl = document.getElementById('modalLikes');
            const commentsEl = document.getElementById('modalComments');
            const photoEl = document.getElementById('modalUserPhoto');

            if (titleEl) titleEl.textContent = `${firstName} ${lastName}'s post`;
            if (userEl) userEl.textContent = `${firstName} ${lastName}`;
            if (timeEl) timeEl.textContent = timestamp;
            if (catEl) catEl.textContent = category;
            if (contentEl) contentEl.innerHTML = content;
            if (likesEl) likesEl.textContent = likes;
            if (commentsEl) commentsEl.textContent = `${commentCount} comment${commentCount !== 1 ? 's' : ''}`;
            if (photoEl) photoEl.src = userPhoto;

            // Set like button state
            const reactBtn = document.getElementById('modalReactBtn');
            const heartIcon = document.getElementById('modalHeartIcon');
            const reactorsBtn = document.getElementById('modalReactorsButton');

            if (reactBtn) reactBtn.dataset.postId = postId;
            if (reactorsBtn) reactorsBtn.setAttribute('postid-data', postId);

            if (heartIcon) {
                if (isLiked) {
                    heartIcon.classList.remove('bg-[#545454]');
                    heartIcon.classList.add('bg-red-600');
                    heartIcon.style.setProperty('--svg', `url('https://api.iconify.design/mdi/heart.svg')`);
                } else {
                    heartIcon.classList.remove('bg-red-600');
                    heartIcon.classList.add('bg-[#545454]');
                    heartIcon.style.setProperty('--svg', `url('https://api.iconify.design/mdi/heart-outline.svg')`);
                }
            }

            // Category icon mapping
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
            const categoryIconEl = document.getElementById('modalCategoryIcon');
            if (categoryIconEl) {
                categoryIconEl.style.setProperty('--svg', `url('https://api.iconify.design/${icon}.svg')`);
            }

            // Set comment form action and dataset
            const form = document.getElementById('modalAddCommentForm');
            if (form) {
                form.dataset.postId = postId;
                form.action = `/feed/posts/${postId}/comments`;
            }

            // Load hover preview for modal reactors button
            fetch(`/feed/posts/${postId}/like-preview`)
                .then(res => res.json())
                .then(data => {
                    if (reactorsBtn && data.preview_users) {
                        // Remove old preview
                        const oldPreview = reactorsBtn.querySelector('.absolute');
                        if (oldPreview) oldPreview.remove();

                        // Create new preview if there are users
                        if (data.preview_users.length > 0) {
                            let previewHTML = '<div class="absolute left-0 top-full mt-2 w-56 bg-white border rounded-lg shadow-lg p-3 text-sm hidden group-hover:block z-50">';

                            data.preview_users.slice(0, 5).forEach(user => {
                                previewHTML += `
                            <div class="flex items-center gap-2 mb-1 pointer-events-none">
                                <img src="${user.photo}" class="w-6 h-6 rounded-full object-cover">
                                <span>${user.first_name} ${user.last_name}</span>
                            </div>
                        `;
                            });

                            if (data.extra_count > 0) {
                                previewHTML += `
                            <div class="text-xs text-gray-500 mt-1 pointer-events-none">
                                +${data.extra_count} more
                            </div>
                        `;
                            }

                            previewHTML += '</div>';
                            reactorsBtn.insertAdjacentHTML('beforeend', previewHTML);
                        }
                    }
                })
                .catch(console.error);

            // Load comments
            const container = document.getElementById('modalCommentsContainer');
            if (container) {
                container.innerHTML = `<div class="px-8 text-sm text-[#545454]">Loading comments...</div>`;
            }
            fetch(`/feed/posts/${postId}/comments`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (!data) return;
                    if (data.success && container) {
                        container.innerHTML = data.comment_html;
                        if (commentsEl) {
                            commentsEl.textContent = `${data.comments_count} comment${data.comments_count !== 1 ? 's' : ''}`;
                        }
                    }
                })
                .catch(console.error);
        });
    </script>

    <!-- ========================= -->
    <!-- COMMENT SUBMIT -->
    <!-- ========================= -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('modalAddCommentForm');
            if (!form) return;
            // Prevent multiple bindings
            if (form.dataset.bound === '1') return;
            form.dataset.bound = '1';

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const postId = form.dataset.postId;
                if (!postId) return alert('Missing postId. Open a post first.');
                const input = form.querySelector('input[name="content"]');
                const content = (input?.value || '').trim();
                if (!content) return;
                const btn = form.querySelector('button[type="submit"]');
                if (btn) btn.disabled = true;
                try {
                    const fd = new FormData();
                    fd.append('content', content);
                    const res = await fetch(`/feed/posts/${postId}/comments`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: fd
                    });
                    const data = await res.json();
                    if (!res.ok || !data.success) {
                        console.error(data);
                        return alert(data?.message || 'Failed to comment');
                    }
                    // Append new comment
                    const container = document.getElementById('modalCommentsContainer');
                    if (container) {
                        container.insertAdjacentHTML('beforeend', data.comment_html);
                    }
                    // Update comment count in modal
                    const commentsEl = document.getElementById('modalComments');
                    if (commentsEl) {
                        commentsEl.textContent = `${data.comments_count} comment${data.comments_count !== 1 ? 's' : ''}`;
                    }
                    // Update feed comment counts (visible text)
                    document.querySelectorAll(`.comment-count[data-post-id="${postId}"]`)
                        .forEach(el => el.textContent = `${data.comments_count} comment${data.comments_count !== 1 ? 's' : ''}`);

                    // UPDATE: Sync all comment modal buttons in feed (for hover preview)
                    document.querySelectorAll(`[target-modal="commentModal"][postid-data="${postId}"]`)
                        .forEach(btn => {
                            btn.setAttribute('comment-data', data.comments_count);
                        });

                    input.value = '';
                } catch (err) {
                    console.error(err);
                    alert('Error submitting comment. Check console.');
                } finally {
                    if (btn) btn.disabled = false;
                }
            });
        });
    </script>

    <!-- ========================= -->
    <!-- COMMENT EDIT/DELETE -->
    <!-- ========================= -->
    <script>
        document.addEventListener('click', async (e) => {
            const item = e.target.closest('.comment-item');
            if (!item) return;
            const commentId = item.dataset.commentId;
            if (!commentId) return;
            const textEl = item.querySelector('.comment-text');
            const editBox = item.querySelector('.comment-edit-box');
            const inputEl = item.querySelector('.comment-input');
            const errEl = item.querySelector('.comment-error');
            const updatedEl = item.querySelector('.comment-updated');

            // EDIT - show edit box
            if (e.target.closest('.btn-edit-comment')) {
                if (editBox) editBox.classList.remove('hidden');
                if (textEl) textEl.classList.add('hidden');
                if (errEl) {
                    errEl.classList.add('hidden');
                    errEl.textContent = '';
                }
                if (inputEl) {
                    inputEl.focus();
                    inputEl.setSelectionRange(inputEl.value.length, inputEl.value.length);
                }
                return;
            }

            // CANCEL
            if (e.target.closest('.btn-cancel-edit')) {
                if (editBox) editBox.classList.add('hidden');
                if (textEl) textEl.classList.remove('hidden');
                if (errEl) {
                    errEl.classList.add('hidden');
                    errEl.textContent = '';
                }
                if (inputEl && textEl) inputEl.value = textEl.textContent.trim();
                return;
            }

            // SAVE
            if (e.target.closest('.btn-save-comment')) {
                const btn = e.target.closest('.btn-save-comment');
                const content = (inputEl?.value || '').trim();

                if (!content) {
                    if (errEl) {
                        errEl.textContent = 'Comment cannot be empty.';
                        errEl.classList.remove('hidden');
                    }
                    return;
                }
                btn.disabled = true;
                try {
                    const res = await fetch(`/feed/comments/${commentId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            content
                        })
                    });
                    const data = await res.json();
                    if (!res.ok || !data.success) {
                        if (errEl) {
                            errEl.textContent = data.message || 'Failed to update comment.';
                            errEl.classList.remove('hidden');
                        }
                        return;
                    }
                    // Update UI
                    if (textEl) textEl.textContent = data.content ?? content;
                    if (updatedEl) updatedEl.textContent = data.updated_human ?? 'just now';
                    if (editBox) editBox.classList.add('hidden');
                    if (textEl) textEl.classList.remove('hidden');
                    if (errEl) {
                        errEl.classList.add('hidden');
                        errEl.textContent = '';
                    }
                } catch (error) {
                    console.error(error);
                    if (errEl) {
                        errEl.textContent = 'Network error.';
                        errEl.classList.remove('hidden');
                    }
                } finally {
                    btn.disabled = false;
                }
                return;
            }

            // DELETE - Show modal instead of confirm()
            if (e.target.closest('.btn-delete-comment')) {
                const deleteModal = document.getElementById('deletecomment');
                const confirmBtn = document.getElementById('confirmDeleteComment');

                if (deleteModal) {
                    // Store comment ID on the modal for later use
                    deleteModal.dataset.deleteCommentId = commentId;
                    deleteModal.classList.remove('hidden');
                }
                return;
            }
        });

        // Handle delete confirmation
        document.addEventListener('click', async (e) => {
            if (e.target.id === 'confirmDeleteComment' || e.target.closest('#confirmDeleteComment')) {
                const deleteModal = document.getElementById('deletecomment');
                const commentId = deleteModal?.dataset.deleteCommentId;

                if (!commentId) return;

                const btn = e.target.closest('#confirmDeleteComment');
                btn.disabled = true;

                try {
                    const res = await fetch(`/feed/comments/${commentId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    });
                    const data = await res.json();
                    if (!res.ok || !data.success) {
                        alert(data.message || 'Failed to delete.');
                        return;
                    }

                    // Remove from UI
                    const item = document.querySelector(`[data-comment-id="${commentId}"]`);
                    if (item) item.remove();

                    // Update counts
                    const postId = data.post_id;
                    const count = data.comments_count;

                    // Update modal comment count
                    const modalComments = document.getElementById('modalComments');
                    if (modalComments) {
                        modalComments.textContent = `${count} comment${count !== 1 ? 's' : ''}`;
                    }

                    // Update feed comment counts
                    document.querySelectorAll(`.comment-count[data-post-id="${postId}"]`)
                        .forEach(el => el.textContent = `${count} comment${count !== 1 ? 's' : ''}`);

                    // Sync all comment modal buttons in feed
                    document.querySelectorAll(`[target-modal="commentModal"][postid-data="${postId}"]`)
                        .forEach(btn => {
                            btn.setAttribute('comment-data', count);
                        });

                    // Close modal
                    deleteModal.classList.add('hidden');
                    delete deleteModal.dataset.deleteCommentId;

                } catch (error) {
                    console.error(error);
                    alert('Network error.');
                } finally {
                    btn.disabled = false;
                }
            }
        });
    </script>

    <!-- ========================= -->
    <!-- 3 DOTS MENU FOR COMMENT BOX -->
    <!-- ========================= -->
    <script>
        document.addEventListener('click', (e) => {
            // Toggle dropdown when dot button is clicked
            const dotBtn = e.target.closest('.dot-btn');
            if (dotBtn) {
                const wrapper = dotBtn.closest('.comment-dropdown-wrapper');
                const menu = wrapper.querySelector('.dropdown-menu');
                menu.classList.toggle('hidden');
                return;
            }

            // Close dropdown if clicking outside
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (!menu.contains(e.target) && !menu.previousElementSibling?.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        });
    </script>

    <!-- ========================= -->
    <!-- PROFILE EDIT MODAL -->
    <!-- ========================= -->
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

            const setVal = (name, val) => {
                const input = modal.querySelector(`input[name="${name}"]`);
                if (input) input.value = val;
            };

            setVal('first_name', firstName);
            setVal('last_name', lastName);
            setVal('bio', bio);
            setVal('instagram', instagram);
            setVal('facebook', facebook);
            setVal('linkedin', linkedin);
            setVal('student_id', studentId);

            modal.classList.remove('hidden');
        });
    </script>

    <!-- ========================= -->
    <!-- EDIT POST -->
    <!-- ========================= -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const modal = document.getElementById('editpostModal');
            const textarea = document.getElementById('editPostContent');
            const form = document.getElementById('editPostForm');

            if (!modal || !textarea || !form) {
                console.error('Modal, textarea, or form not found!');
                return;
            }

            let currentPostId = null;

            // OPEN MODAL
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.editPostBtn');
                if (!btn) return;

                e.preventDefault();
                e.stopPropagation();

                currentPostId = btn.dataset.postId;
                textarea.value = btn.dataset.postContent || '';

                modal.classList.remove('hidden');
            });

            // SAVE (AJAX)
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (!currentPostId) return;

                try {
                    // Safe CSRF token retrieval
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

                    if (!csrfToken) {
                        console.warn('CSRF token not found');
                    }

                    const res = await fetch(`/feed/posts/${currentPostId}/edit`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            content: textarea.value
                        })
                    });

                    const data = await res.json();

                    if (!res.ok || !data.success) throw new Error('Failed');

                    modal.classList.add('hidden');
                    window.location.reload();

                } catch (err) {
                    console.error(err);
                    alert('Edit failed. Check console.');
                }
            });

            // CLOSE MODAL
            modal.querySelectorAll('[close-modal]').forEach(btn => {
                btn.addEventListener('click', () => modal.classList.add('hidden'));
            });

            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.add('hidden');
            });

        });
    </script>

    <!-- ========================= -->
    <!-- REPORT MODAL -->
    <!-- ========================= -->
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
                console.error('Report modal: missing postid-data or #reportForm');
            }
        });
    </script>

    <!-- ========================= -->
    <!-- LIKE / REACT SYNC -->
    <!-- ========================= -->
    <script>
        function syncLikes(postId, liked, likesCount, previewUsers, extraCount) {
            // Sync hearts in feed
            document.querySelectorAll(`.react-btn[data-post-id="${postId}"] [data-heart-icon]`)
                .forEach(icon => {
                    icon.style.setProperty('--svg', `url('https://api.iconify.design/${liked ? 'mdi:heart' : 'mdi:heart-outline'}.svg')`);
                    icon.classList.toggle('bg-red-600', liked);
                    icon.classList.toggle('bg-[#545454]', !liked);
                });

            // Sync like counts in feed (visible text)
            document.querySelectorAll(`.like-count[data-post-id="${postId}"]`)
                .forEach(el => el.textContent = `❤️ ${likesCount}`);

            // UPDATE: Sync hover preview for likes in FEED
            const hoverButton = document.querySelector(`button[target-modal="reactorsModal"][postid-data="${postId}"]`);
            if (hoverButton && previewUsers) {
                // Remove old hover preview
                const oldPreview = hoverButton.querySelector('.absolute');
                if (oldPreview) oldPreview.remove();

                // Create new hover preview if there are users (only first 5)
                if (previewUsers.length > 0) {
                    let previewHTML = '<div class="absolute left-0 top-full mt-2 w-56 bg-white border rounded-lg shadow-lg p-3 text-sm hidden group-hover:block z-50">';

                    previewUsers.slice(0, 5).forEach(user => {
                        previewHTML += `
                    <div class="flex items-center gap-2 mb-1 pointer-events-none">
                        <img src="${user.photo}" class="w-6 h-6 rounded-full object-cover">
                        <span>${user.first_name} ${user.last_name}</span>
                    </div>
                `;
                    });

                    if (extraCount > 0) {
                        previewHTML += `
                    <div class="text-xs text-gray-500 mt-1 pointer-events-none">
                        +${extraCount} more
                    </div>
                `;
                    }

                    previewHTML += '</div>';
                    hoverButton.insertAdjacentHTML('beforeend', previewHTML);
                }
            }

            // UPDATE: Sync hover preview for likes in MODAL
            const modalReactorsBtn = document.getElementById('modalReactorsButton');
            if (modalReactorsBtn && previewUsers) {
                // Remove old hover preview
                const oldPreview = modalReactorsBtn.querySelector('.absolute');
                if (oldPreview) oldPreview.remove();

                // Create new hover preview if there are users (only first 5)
                if (previewUsers.length > 0) {
                    let previewHTML = '<div class="absolute left-0 top-full mt-2 w-56 bg-white border rounded-lg shadow-lg p-3 text-sm hidden group-hover:block z-50">';

                    previewUsers.slice(0, 5).forEach(user => {
                        previewHTML += `
                    <div class="flex items-center gap-2 mb-1 pointer-events-none">
                        <img src="${user.photo}" class="w-6 h-6 rounded-full object-cover">
                        <span>${user.first_name} ${user.last_name}</span>
                    </div>
                `;
                    });

                    if (extraCount > 0) {
                        previewHTML += `
                    <div class="text-xs text-gray-500 mt-1 pointer-events-none">
                        +${extraCount} more
                    </div>
                `;
                    }

                    previewHTML += '</div>';
                    modalReactorsBtn.insertAdjacentHTML('beforeend', previewHTML);
                }
            }

            // UPDATE: Sync all comment modal buttons' likes-data attribute
            document.querySelectorAll(`[target-modal="commentModal"][postid-data="${postId}"]`)
                .forEach(btn => {
                    btn.setAttribute('likes-data', likesCount);
                    btn.setAttribute('liked-data', liked ? '1' : '0');
                });

            // Sync modal like count
            const modalLikes = document.getElementById('modalLikes');
            const modalHeartIcon = document.getElementById('modalHeartIcon');
            const modalReactBtn = document.getElementById('modalReactBtn');
            if (modalLikes && modalReactBtn && modalReactBtn.dataset.postId == postId) {
                modalLikes.textContent = likesCount;
                if (modalHeartIcon) {
                    modalHeartIcon.style.setProperty('--svg', `url('https://api.iconify.design/${liked ? 'mdi:heart' : 'mdi:heart-outline'}.svg')`);
                    modalHeartIcon.classList.toggle('bg-red-600', liked);
                    modalHeartIcon.classList.toggle('bg-[#545454]', !liked);
                }
            }
        }

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.react-btn');
            if (!btn) return;
            const postId = btn.dataset.postId;
            if (!postId) return;

            fetch(`/feed/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (!data) return;
                    syncLikes(postId, data.liked, data.likes_count, data.preview_users || [], data.extra_count || 0);
                })
                .catch(console.error);
        });
    </script>

    <!-- HEART REACT ANIMATION -->
    <script>
        document.querySelectorAll('.react-btn').forEach(btn => {
            btn.addEventListener('mousedown', () => {
                btn.classList.add('clicked');
            });
        });
    </script>

    <script>
        // Automatically find all elements with class 'modal'
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                // Check if click is on the backdrop
                if (e.target.classList.contains('bg-black/35') || e.target === this) {
                    this.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });
        });

        // Generic function to open any modal
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }
    </script>

    <script>
        // AUTO-DETECT: Prevent scroll when ANY modal opens
        document.querySelectorAll('.modal').forEach(modal => {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        const hasOpenModal = Array.from(document.querySelectorAll('.modal'))
                            .some(m => !m.classList.contains('hidden'));

                        document.body.style.overflow = hasOpenModal ? 'hidden' : '';
                    }
                });
            });

            observer.observe(modal, {
                attributes: true
            });
        });
    </script>

    <script>
        // Sync like count visibility when reacting
        document.querySelectorAll('.react-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const postId = this.getAttribute('data-post-id');

                // Wait for AJAX to complete, then update UI
                setTimeout(() => {
                    const likeContainer = document.querySelector(`.like-count-container[data-post-id="${postId}"]`);
                    const likeCountSpan = likeContainer?.querySelector('.like-count');

                    if (likeContainer && likeCountSpan) {
                        const currentCount = parseInt(likeCountSpan.textContent.match(/\d+/)[0]);

                        // Show/hide based on count
                        if (currentCount > 0) {
                            likeContainer.classList.remove('invisible');
                        } else {
                            likeContainer.classList.add('invisible');
                        }
                    }
                }, 500); // Adjust timing based on your AJAX response
            });
        });

        // Sync comment count visibility when commenting
        document.querySelectorAll('.comment-count').forEach(commentCount => {
            const observer = new MutationObserver(() => {
                const text = commentCount.textContent.trim();
                const count = parseInt(text.match(/\d+/)?.[0] || 0);

                if (count > 0) {
                    commentCount.classList.remove('invisible');
                } else {
                    commentCount.classList.add('invisible');
                }
            });

            observer.observe(commentCount, {
                childList: true,
                characterData: true,
                subtree: true
            });
        });
    </script>