<div class="flex items-start gap-2 mb-4 px-8">
    <img src="{{ $comment->author?->photo ? asset('storage/'.$comment->author->photo) : asset('/img/user.png') }}" alt="" class="w-8 h-8 rounded-full object-cover cursor-pointer border-2 border-gray-300">
    <div class="flex flex-col text-sm">
        <span class="font-bold">{{ $comment->author?->first_name }} {{ $comment->author?->last_name }}</span>
        <p>{{ $comment->content }}</p>
    </div>
</div>
