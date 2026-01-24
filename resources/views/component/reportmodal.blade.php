<div id="reportModal" class="modal hidden">
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black/35 w-full h-screen flex justify-center items-center z-50 backdrop-blur-[1px]">
        <div class="w-2/5 bg-[#F5F5F5] form-shadow rounded-3xl backdrop-blur-sm overflow-hidden">

            <div class="shadow-postheader w-full pb-3 pt-7 relative">
                <h2 class="text-center text-xl font-medium">Report Post</h2>
                <p class="text-center text-sm">Why are you reporting this post? We will review it.</p>

                <span close-modal
                      class="icon bg-black absolute top-10 right-0 -translate-x-1/2 -translate-y-1/2 transition-all duration-300 cursor-pointer"
                      style="--svg: url('https://api.iconify.design/material-symbols-light/close-rounded.svg'); --size: 35px; --icon-color: black;"></span>
            </div>

            {{-- ✅ NO action here; JS will set action to /posts/{id}/report --}}
            <form id="reportForm" method="POST" class="flex flex-col gap-3.5 p-8">
                @csrf

                <input type="hidden" name="post_id" id="reportPostId">

                <div class="flex flex-col">
                    <label>Select reason</label>
                    <select name="reason" required class="w-full bg-black/15 px-4 py-2 rounded-md border-none focus:outline-none">
                        <option selected disabled value="">Reason</option>
                        <option value="spam">Spam or misleading</option>
                        <option value="harassment">Harassment or hate speech</option>
                        <option value="inappropriate">Inappropriate content</option>
                        <option value="violence">Violence or threats</option>
                        <option value="sexual">Sexual content</option>
                        <option value="false_info">False information</option>
                        <option value="scam">Scam or fraud</option>
                        <option value="copyright">Copyright violation</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label>Additional details</label>
                    <textarea name="details" class="shadow-textarea w-full h-25 px-3 focus:outline-none resize-none"></textarea>
                </div>

                <div class="flex justify-center">
                    <button type="submit"
                        class="w-30 flex justify-center items-center py-1.5 text-xl font-medium text-white bg-[#770d08] rounded-md">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('click', function(e) {
    const btn = e.target.closest('[target-modal="reportModal"]');
    if (!btn) return;

    const modal = document.getElementById('reportModal');
    const form  = document.getElementById('reportForm');
    const postId = btn.getAttribute('postid-data');

    if (!modal || !form || !postId) {
        console.error('Report modal error:', { modal, form, postId });
        return;
    }

    form.action = `/posts/${postId}/report`; // ✅ CORRECT ROUTE
    modal.classList.remove('hidden');
});
</script>
