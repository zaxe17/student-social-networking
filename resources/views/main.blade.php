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
    <!-- 3 DOT DROPDOWN -->
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

                dropdowns.forEach(d => { if (d !== dropdown) d.classList.add('hidden'); });
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
    <!-- (NO COMMENT SUBMIT HERE to avoid double) -->
    <!-- ========================= -->
    <script>
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[target-modal="commentModal"]');
        if (!btn) return;

        const postId = btn.getAttribute('postid-data');
        if (!postId) {
            console.error('Missing postid-data');
            return;
        }

        // open modal
        const modal = document.getElementById('commentModal');
        if (modal) modal.classList.remove('hidden');

        // fill modal texts
        const firstName = btn.getAttribute('firstname-data') || '';
        const lastName = btn.getAttribute('lastname-data') || '';
        const timestamp = btn.getAttribute('timestamp-data') || '';
        const category = btn.getAttribute('category-data') || '';
        const content = btn.getAttribute('content-data') || '';
        const likes = btn.getAttribute('likes-data') || '0';
        const commentCount = parseInt(btn.getAttribute('comment-data') || '0', 10);
        const isLiked = btn.getAttribute('liked-data') === '1';
        const userPhoto = btn.getAttribute('userphoto-data') || "{{ asset('/img/user.png') }}";

        const titleEl = document.getElementById('modalTitle');
        const userEl = document.getElementById('modalUserName');
        const timeEl = document.getElementById('modalTimestamp');
        const catEl  = document.getElementById('modalCategoryName');
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

        // set like button post id
        const reactBtn = document.getElementById('modalReactBtn');
        const heartIcon = document.getElementById('modalHeartIcon');
        if (reactBtn) reactBtn.dataset.postId = postId;

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

        // ✅ set comment form action + dataset (matches /feed routes)
        const form = document.getElementById('modalAddCommentForm');
        if (form) {
            form.dataset.postId = postId;
            form.action = `/feed/posts/${postId}/comments`;
        }

        // ✅ load comments (matches /feed routes)
        const container = document.getElementById('modalCommentsContainer');
        if (container) container.innerHTML = `<div class="px-8 text-sm text-[#545454]">Loading comments...</div>`;

        fetch(`/feed/posts/${postId}/comments`, { headers: { 'Accept': 'application/json' } })
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
    <!-- COMMENT SUBMIT (ONLY ONE HANDLER) -->
    <!-- ========================= -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('modalAddCommentForm');
        if (!form) return;

        // prevent multiple bindings if layout is injected twice
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

                // append ONCE
                document.getElementById('modalCommentsContainer')
                    ?.insertAdjacentHTML('beforeend', data.comment_html);

                document.getElementById('modalComments').textContent =
                    `${data.comments_count} comment${data.comments_count !== 1 ? 's' : ''}`;

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
    <!-- PROFILE EDIT MODAL OPEN -->
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

        modal.querySelector('input[name="first_name"]') && (modal.querySelector('input[name="first_name"]').value = firstName);
        modal.querySelector('input[name="last_name"]') && (modal.querySelector('input[name="last_name"]').value = lastName);
        modal.querySelector('input[name="bio"]') && (modal.querySelector('input[name="bio"]').value = bio);
        modal.querySelector('input[name="instagram"]') && (modal.querySelector('input[name="instagram"]').value = instagram);
        modal.querySelector('input[name="facebook"]') && (modal.querySelector('input[name="facebook"]').value = facebook);
        modal.querySelector('input[name="linkedin"]') && (modal.querySelector('input[name="linkedin"]').value = linkedin);

        const studentIdInput = modal.querySelector('input[name="student_id"]');
        if (studentIdInput) studentIdInput.value = studentId;

        modal.classList.remove('hidden');
    });
    </script>

    <!-- ========================= -->
    <!-- EDIT POST -->
    <!-- ========================= -->
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

    <!-- ========================= -->
    <!-- REPORT MODAL (MATCHES /feed ROUTES) -->
    <!-- ========================= -->
    <script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[target-modal="reportModal"]');
        if (!btn) return;

        const modal = document.getElementById('reportModal');
        if (modal) modal.classList.remove('hidden');

        const postId = btn.getAttribute('postid-data');
        const form = document.getElementById('reportForm');

        if (form && postId) form.action = `/feed/posts/${postId}/report`;
        else console.error('Report modal: missing postid-data or #reportForm');
    });
    </script>

    <!-- ========================= -->
    <!-- SEARCH BAR LOGIC -->
    <!-- ========================= -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchInput');
        const resultsContainer = document.getElementById('searchResults');
        if (!searchInput || !resultsContainer) return;

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
                    .then(res => res.json())
                    .then(data => {
                        resultsContainer.innerHTML = '';

                        data.posts?.forEach(post => {
                            const div = document.createElement('div');
                            div.className = "px-3 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-200";
                            div.innerHTML = `<strong>Post:</strong> ${post.content.substring(0, 50)}...`;
                            div.addEventListener('click', () => window.location.href = `/feed#post-${post.post_id}`);
                            resultsContainer.appendChild(div);
                        });

                        data.categories?.forEach(cat => {
                            const div = document.createElement('div');
                            div.className = "px-3 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-200";
                            div.innerHTML = `<strong>Category:</strong> ${cat.category_name}`;
                            div.addEventListener('click', () => window.location.href = `/feed/category?category[]=${cat.category_id}`);
                            resultsContainer.appendChild(div);
                        });

                        data.students?.forEach(user => {
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

    <!-- ========================= -->
    <!-- LIKE / REACT SYNC -->
    <!-- ========================= -->
    <script>
    function syncLikes(postId, liked, likesCount) {
        document.querySelectorAll(`.react-btn[data-post-id="${postId}"] [data-heart-icon]`)
            .forEach(icon => {
                icon.style.setProperty('--svg', `url('https://api.iconify.design/${liked ? 'mdi:heart' : 'mdi:heart-outline'}.svg')`);
                icon.classList.toggle('bg-red-600', liked);
                icon.classList.toggle('bg-[#545454]', !liked);
            });

        document.querySelectorAll(`.like-count[data-post-id="${postId}"]`)
            .forEach(el => el.textContent = `❤️ ${likesCount}`);

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
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data) return;
            syncLikes(postId, data.liked, data.likes_count);
        })
        .catch(console.error);
    });
    </script>
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

  // =========================
  // EDIT (show edit box)
  // =========================
  if (e.target.closest('.btn-edit-comment')) {
    if (editBox) editBox.classList.remove('hidden');
    if (textEl) textEl.classList.add('hidden');
    if (errEl) { errEl.classList.add('hidden'); errEl.textContent = ''; }

    // focus cursor end
    if (inputEl) {
      inputEl.focus();
      inputEl.setSelectionRange(inputEl.value.length, inputEl.value.length);
    }
    return;
  }

  // =========================
  // CANCEL
  // =========================
  if (e.target.closest('.btn-cancel-edit')) {
    if (editBox) editBox.classList.add('hidden');
    if (textEl) textEl.classList.remove('hidden');
    if (errEl) { errEl.classList.add('hidden'); errEl.textContent = ''; }

    // reset input to original text
    if (inputEl && textEl) inputEl.value = textEl.textContent.trim();
    return;
  }

  // =========================
  // SAVE (PUT)
  // =========================
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
        body: JSON.stringify({ content })
      });

      const data = await res.json();
      if (!res.ok || !data.success) {
        if (errEl) {
          errEl.textContent = data.message || 'Failed to update comment.';
          errEl.classList.remove('hidden');
        }
        return;
      }

      // update UI
      if (textEl) textEl.textContent = data.content ?? content;
      if (updatedEl) updatedEl.textContent = data.updated_human ?? 'just now';

      if (editBox) editBox.classList.add('hidden');
      if (textEl) textEl.classList.remove('hidden');
      if (errEl) { errEl.classList.add('hidden'); errEl.textContent = ''; }

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

  // =========================
  // DELETE (DELETE)
  // =========================
  if (e.target.closest('.btn-delete-comment')) {
    const btn = e.target.closest('.btn-delete-comment');
    if (!confirm('Delete this comment?')) return;

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

      // remove from UI
      item.remove();

      // update counts (modal + feed)
      const postId = data.post_id;
      const count = data.comments_count;

      const modalComments = document.getElementById('modalComments');
      if (modalComments) {
        modalComments.textContent = `${count} comment${count !== 1 ? 's' : ''}`;
      }

      document.querySelectorAll(`.comment-count[data-post-id="${postId}"]`)
        .forEach(el => el.textContent = `${count} comment${count !== 1 ? 's' : ''}`);

    } catch (error) {
      console.error(error);
      alert('Network error.');
    } finally {
      btn.disabled = false;
    }
    return;
  }
});
</script>


    <!-- OPTIONAL external modal js -->
    <script src="{{ asset('js/modal.js') }}"></script>
</body>
</html>
