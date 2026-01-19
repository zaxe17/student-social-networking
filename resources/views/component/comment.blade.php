@forelse($post->comments as $c)
<div class="flex items-start gap-2 mb-8 px-8">
    <img src="{{ $c->author?->photo ? asset('storage/' . $c->author->photo) : asset('/img/user.png') }}"
        class="w-8 h-8 rounded-full object-cover" />


    <div class="flex flex-col text-sm">
        <span class="font-bold">
            {{ $c->author?->first_name }} {{ $c->author?->last_name }}
        </span>

        <p>
            {{ $c->content }}
        </p>
    </div>
</div>
@empty
<div class="px-8 text-sm text-[#545454]">
    No comments yet.
</div>
@endforelse