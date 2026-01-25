@extends('layout.app')

@section('title', 'Events | ISKOnnect')
@section('page')
@include('layout.navbar')
@include('layout.sidebar', ['loggedInStudent' => $loggedInStudent])
@include('component.changepassmodal', ['title' => 'Change password'])

<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800">Events</h1>

        <button type="button"
            class="bg-[#6b1d1d] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90"
            onclick="document.getElementById('createEventModal')?.classList.remove('hidden')">
            Add new event
        </button>
    </div>

    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
            @include('component.eventcard', ['event' => $event])
        @empty
            <p class="text-sm text-gray-500">No upcoming events.</p>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $events->links() }}
    </div>
</div>

@include('component.createeventmodal')
@include('component.deleteconfirm', [
'title' => 'Delete account',
'modal_id' => 'deleteaccount',
'route' => route('student.delete')
])
@endsection