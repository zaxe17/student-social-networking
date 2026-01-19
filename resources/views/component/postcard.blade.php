<!-- POST BOX -->
<div class="shadow-bg px-10 pt-7 pb-4 mb-5">
    <!-- HEADER -->
    <div class="flex items-start justify-between mb-6">
        <div class="flex items-center gap-3 text-sm">
            <img src="{{ $post->author?->photo ? asset('storage/' . $post->author->photo) : asset('/img/user.png') }}"
                class="w-7 h-7 rounded-full object-cover" />


            <div class="flex flex-col">
                <span class="font-semibold">
                    {{ $post->author?->first_name }} {{ $post->author?->last_name }}
                    @if(auth()->check() && auth()->user()->student_id === $post->student_id)
                    <span class="text-[#545454] font-normal">(You)</span>
                    @endif
                </span>

                <div class="flex items-center gap-2 text-[#545454]">
                    <span>{{ $post->created_at?->diffForHumans() }}</span>
                    @if($post->category)
                    <span>•</span>
                    <span class="icon bg-[#770d08]"
                        style="--svg: url('https://api.iconify.design/mdi/book-open-variant.svg'); --size: 18px;">
                    </span>
                    <span>{{ $post->category->category_name }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- DROPDOWN only for owner --}}
        @if(auth()->check() && auth()->user()->student_id === $post->student_id)
        <div class="relative">
            <span class="icon bg-[#545454] cursor-pointer"
                onclick="document.getElementById('dotDropdown-{{ $post->post_id }}').classList.toggle('hidden')"
                style="--svg: url('https://api.iconify.design/solar/menu-dots-bold.svg'); --size: 25px;">
            </span>

            <div id="dotDropdown-{{ $post->post_id }}"
                class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg border border-gray-200 hidden z-50">

                <ul class="py-2 text-sm">
                    {{-- EDIT (simple version: inline form toggle not included) --}}
                    <li class="px-4 py-2 cursor-pointer flex items-center gap-1.5"
                        onclick="document.getElementById('editBox-{{ $post->post_id }}').classList.toggle('hidden')">
                        <span class="icon bg-[#545454]" style="--svg: url('https://api.iconify.design/mdi/edit-outline.svg'); --size: 18px;"></span>
                        Edit
                    </li>

                    {{-- ARCHIVE (soft delete) --}}
                    <li class="px-4 py-2 cursor-pointer flex items-center gap-1.5 text-red-600">
                        <form action="{{ route('posts.destroy', $post->post_id) }}" method="POST" class="flex items-center gap-1.5 w-full">
                            @csrf
                            @method('DELETE')
                            <span class="icon bg-red-600" style="--svg: url('https://api.iconify.design/mdi/delete-outline.svg'); --size: 18px;"></span>
                            <button type="submit" class="w-full text-left">Archive</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        @endif
    </div>

    {{-- EDIT BOX (simple) --}}
    @if(auth()->check() && auth()->user()->student_id === $post->student_id)
    <div id="editBox-{{ $post->post_id }}" class="hidden mb-4">
        <form action="{{ route('posts.update', $post->post_id) }}" method="POST" class="flex flex-col gap-2">
            @csrf
            @method('PATCH')

            <textarea name="content" class="w-full bg-[#dde0e5] p-2 rounded-md focus:outline-none" rows="3" required>{{ $post->content }}</textarea>

            <div class="flex gap-2">
                <button type="submit" class="bg-[#770d08] text-white px-4 py-1.5 rounded-md">Save</button>
                <button type="button" class="border px-4 py-1.5 rounded-md"
                    onclick="document.getElementById('editBox-{{ $post->post_id }}').classList.add('hidden')">
                    Cancel
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- CONTENT PARAG -->
    <p class="border-b border-b-black/50 border-solid pb-5 mb-3.5 whitespace-pre-line">
        {{ $post->content }}
    </p>

    <!-- REACT AND COMMENT COUNT -->
    <div class="flex justify-between items-center">
        <!-- HEART -->
        <form action="{{ route('posts.like', $post->post_id) }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center cursor-pointer">
                <span>❤️</span>
                <span class="text-sm text-[#545454]">{{ $post->likes_count ?? 0 }}</span>
            </button>
        </form>

        <span class="text-[#545454] text-sm">
            {{ $post->comments_count ?? 0 }} comment
        </span>
    </div>

    <!-- ADD COMMENT (simple inline) -->
    <div class="mt-3">
        <form action="{{ route('comments.store', $post->post_id) }}" method="POST" class="flex items-center gap-3">
            @csrf
            <input type="text" name="content" required
                placeholder="write a comment..."
                class="w-full h-10 px-2.5 rounded-lg bg-[#dde0e5] focus:outline-none placeholder:text-[#545454]">
            <button type="submit" class="bg-[#770d08] text-white px-4 py-2 rounded-md">Send</button>
        </form>
    </div>

    {{-- SHOW COMMENTS (simple) --}}
    @if($post->comments && $post->comments->count())
    <div class="mt-4 space-y-2">
        @foreach($post->comments as $c)
        <div class="text-sm">
            <span class="font-semibold">
                {{ $c->author?->first_name }} {{ $c->author?->last_name }}:
            </span>
            <span class="text-[#545454]">{{ $c->content }}</span>
        </div>
        @endforeach
    </div>
    @endif
</div>