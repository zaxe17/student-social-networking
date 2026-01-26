@extends('layout.app')
@section('title', 'Admin Dashboard | ISKOnnect')
@section('page')

<div class="h-screen bg-gray-100 flex flex-col">

    {{-- NAVBAR --}}
    <nav class="bg-[#770d08] px-10 h-18 flex justify-between items-center shrink-0">
        <div class="flex items-center gap-4">
            <img src="/img/ISKOnnect.png" alt="ISKOnnect" class="w-30">
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="text-white underline hover:text-gray-200 cursor-pointer">
                Logout
            </button>
        </form>
    </nav>

    {{-- PAGE CONTENT --}}
    <div class="flex-1 max-w-7xl mx-auto px-4 py-6 overflow-hidden w-full">

        {{-- CARD --}}
        <div class="bg-white rounded-2xl shadow-lg p-8 flex flex-col h-full">

            {{-- HEADER --}}
            <div class="flex justify-between items-center mb-6 shrink-0">
                <h2 class="text-3xl font-semibold">
                    Reported Posts Management
                </h2>
                <input
                    id="searchInput"
                    class="bg-gray-200 rounded-full px-4 py-2 text-sm"
                    placeholder="Search by Content">
            </div>

            {{-- SUCCESS / ERROR --}}
            <div class="mb-4 shrink-0">
                @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-2">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
                @endif
            </div>

            {{-- TABLE SCROLL AREA --}}
            <div class="flex-1 overflow-y-auto no-scrollbar">

                <table class="w-full border-separate border-spacing-1">

                    <thead class="bg-[#770d08] text-white sticky top-0 z-20">
                        <tr>
                            <th class="p-3 text-left">ID</th>
                            <th class="p-3 text-left">Post ID</th>
                            <th class="p-3 text-left">Posted By</th>
                            <th class="p-3 text-left">Content</th>
                            <th class="p-3 text-left">Reason</th>
                            <th class="p-3 text-left">Additional Details</th>
                            <th class="p-3 text-center">Total Reports</th>
                            <th class="p-3 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($reports as $postId => $postReports)
                        <tr class="hover:bg-gray-50">

                            <td class="bg-gray-700 text-white p-3 text-center font-semibold">
                                {{ $postReports->first()->id }}
                            </td>

                            <td class="bg-gray-100 p-3 text-center">
                                {{ $postId }}
                            </td>

                            <td class="bg-gray-100 p-3">
                                @if($postReports->first()->post && $postReports->first()->post->author)
                                <div class="text-sm">
                                    <div class="font-semibold">
                                        {{ $postReports->first()->post->author->first_name }}
                                        {{ $postReports->first()->post->author->last_name }}
                                    </div>
                                    <div class="text-gray-500 text-xs">
                                        {{ $postReports->first()->post->author->student_id }}
                                    </div>
                                </div>
                                @else
                                <span class="text-gray-400">Unknown</span>
                                @endif
                            </td>

                            <td class="bg-gray-100 p-3">
                                @if($postReports->first()->post)
                                <p class="text-sm line-clamp-3">
                                    {{ $postReports->first()->post->content }}
                                </p>
                                @else
                                <span class="text-gray-400">Post deleted</span>
                                @endif
                            </td>

                            <td class="bg-gray-100 p-3">
                                @foreach($postReports as $report)
                                <span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded mr-1 mb-1">
                                    {{ ucfirst(str_replace('_',' ', $report->reason ?? 'other')) }}
                                </span>
                                @endforeach
                            </td>

                            <td class="bg-gray-100 p-3">
                                @foreach($postReports as $report)
                                @if($report->details)
                                <div class="text-sm text-gray-700 mb-1 line-clamp-3">
                                    • {{ $report->details }}
                                </div>
                                @endif
                                @endforeach
                            </td>

                            <td class="bg-gray-100 p-3 text-center font-semibold">
                                {{ $postReports->count() }}
                            </td>

                            <td class="bg-gray-100 p-3">
                                <div class="flex gap-2 justify-center">

                                    <form method="POST"
                                        action="{{ route('admin.reports.delete', $postReports->first()->id) }}"
                                        onsubmit="return confirm('Delete this report?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs px-3 py-2 rounded">
                                            Delete Record
                                        </button>
                                    </form>

                                    @if($postReports->first()->post && !$postReports->first()->post->trashed())
                                    <form method="POST"
                                        action="{{ route('admin.posts.delete', $postReports->first()->id) }}"
                                        onsubmit="return confirm('Delete this post permanently?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-2 rounded">
                                            Delete Post
                                        </button>
                                    </form>
                                    @else
                                    <button disabled
                                        class="bg-gray-300 text-gray-500 text-xs px-3 py-2 rounded">
                                        Post Deleted
                                    </button>
                                    @endif

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-gray-500">
                                No reports found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>
    </div>
</div>

{{-- SEARCH --}}
<script>
    document.getElementById('searchInput')?.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            const cell = row.children[3];
            if (!cell) return;
            row.style.display = cell.textContent.toLowerCase().includes(term) ? '' : 'none';
        });
    });
</script>

@endsection