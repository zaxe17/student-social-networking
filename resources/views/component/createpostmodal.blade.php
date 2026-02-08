<div id="createPostModal" class="modal hidden">
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black/35 w-full h-screen flex justify-center lg:items-center items-end z-50 backdrop-blur-[1px]">
        <div class="lg:w-2/5 w-full bg-[#F5F5F5] form-shadow rounded-3xl backdrop-blur-sm overflow-hidden">

            <!-- HEADER OF MODAL -->
            <div class="shadow-postheader w-full pb-3 pt-7 relative">
                <!-- TITLE -->
                <h2 class="text-center text-xl font-medium">Create New Post</h2>

                <!-- CLOSE BUTTON -->
                <span close-modal class="icon bg-black absolute top-10 right-0 -translate-x-1/2 -translate-y-1/2 transition-all duration-300 cursor-pointer" style="--svg: url('https://api.iconify.design/material-symbols-light/close-rounded.svg'); --size: 35px; --icon-color: black;"></span>
            </div>

            <!-- POST FORM -->
            <form action="{{ route('posts.store') }}" method="POST" class="flex flex-col gap-3.5 p-8" novalidate>
                @csrf

                <!-- STUDENT DISPLAY -->
                <div class="flex items-center gap-5">
                    <!-- STUDENT IMG -->
                    <img id="modalCommentUserPhoto"
                        src="{{ $student && $student->photo ? asset('/storage/'.$student->photo) : asset('/img/user.png') }}"
                        alt="User Photo"
                        class="w-10 h-10 rounded-full object-cover border-2 border-gray-300">
                    <!-- STUDENT NAME -->
                    <span class="text-xl">{{ $student->first_name ?? 'Guest' }} {{ $student->last_name ?? '' }}</span>
                </div>

                <!-- POST CONTENT FIELD -->
                <textarea name="content" placeholder="Type something..." class="w-full h-25 px-3 focus:outline-none resize-none" required></textarea>

                <!-- CATEGORY SELECT -->
                <select name="category_id" class="w-full bg-black/15 px-4 py-2 rounded-md border-none focus:outline-none" aria-label="Category">
                    @foreach($categories as $cat)
                    <option value="{{ $cat->category_id }}"
                        {{ $cat->category_name === 'Miscellaneous' ? 'selected' : '' }}>
                        {{ $cat->category_name }}
                    </option>
                    @endforeach
                </select>

                <!-- SUBMIT BUTTON -->
                <div class="flex justify-center">
                    <button type="submit" class="lg:w-30 w-full flex justify-center items-center py-1.5 text-xl font-medium text-white bg-[#770d08] rounded-md">Post</button>
                </div>
            </form>
        </div>
    </div>
</div>