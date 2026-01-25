<div id="editpostModal" class="modal hidden">
    <div
        class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black/35 w-full h-screen flex justify-center items-center z-50 backdrop-blur-[1px]">

        <div class="w-2/5 bg-[#F5F5F5] form-shadow rounded-3xl overflow-hidden">

            <!-- HEADER -->
            <div class="shadow-postheader w-full pb-3 pt-7 relative">
                <h2 class="text-center text-xl font-medium">Edit post</h2>

                <!-- Close Button -->
                <span close-modal class="icon bg-black absolute top-10 right-0 -translate-x-1/2 -translate-y-1/2 transition-all duration-300 cursor-pointer" style="--svg: url('https://api.iconify.design/material-symbols-light/close-rounded.svg'); --size: 35px; --icon-color: black;"></span>
            </div>

            <!-- FORM -->
            <form id="editPostForm" method="POST" class="flex flex-col gap-4 p-8">
                @csrf
                @method('PUT')

                <!-- USER -->
                <div class="flex items-center gap-4">
                    <img
                        src="{{ $student && $student->photo ? asset('storage/'.$student->photo) : asset('/img/user.png') }}"
                        class="w-10 h-10 rounded-full object-cover border">
                    <span class="text-lg">
                        {{ $student->first_name ?? 'Guest' }} {{ $student->last_name ?? '' }}
                    </span>
                </div>

                <!-- CONTENT -->
                <textarea
                    id="editPostContent"
                    name="content"
                    class="w-full min-h-[120px] px-3 py-2 resize-none focus:outline-none"
                    required>
                </textarea>

                <!-- SAVE -->
                <div class="flex justify-center">
                    <button type="submit"
                        class="px-8 py-2 text-lg font-medium text-white bg-[#770d08] rounded-md">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>