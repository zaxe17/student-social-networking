@extends('layout.app')
@section('title', 'Admin Dashboard | ISKOnnect')
@section('page')

<div class="min-h-screen bg-gray-100">
    <nav class="bg-[#770d08] px-10 py-4 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <img src="/img/logo.png" class="w-10 h-10">
            <p class="text-2xl text-white font-bold">ISKOnnect</p>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="text-white underline hover:text-gray-200 cursor-pointer">Logout</button>
        </form>
    </nav>

    <div class="max-w-7xl mx-auto py-10 px-4">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-semibold">Reported Posts Management</h2>
                <input class="bg-gray-200 rounded-full px-4 py-2 text-sm" placeholder="Search by Content" id="searchInput">
            </div>

            {{-- Success/Error Messages --}}
            @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                {{ session('error') }}
            </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full border-separate border-spacing-1">
                    <thead class="bg-[#770d08] text-white">
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
                            {{-- ID --}}
                            <td class="bg-gray-700 text-white p-3 text-center font-semibold">
                                {{ $postReports->first()->id }}
                            </td>

                            {{-- Post ID --}}
                            <td class="bg-gray-100 p-3 text-center">
                                {{ $postId }}
                            </td>

                            {{-- Posted By --}}
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

                            {{-- Content --}}
                            <td class="bg-gray-100 p-3">
                                @if($postReports->first()->post)
                                <div class="max-w-xs">
                                    <p class="text-sm line-clamp-3">{{ $postReports->first()->post->content }}</p>
                                </div>
                                @else
                                <span class="text-gray-400">Post deleted</span>
                                @endif
                            </td>

                            {{-- Reasons --}}
                            <td class="bg-gray-100 p-3">
                                @php
                                $reasonMap = [
                                'spam' => 'Spam or misleading',
                                'harassment' => 'Harassment or hate speech',
                                'inappropriate' => 'Inappropriate content',
                                'violence' => 'Violence or threats',
                                'sexual' => 'Sexual content',
                                'false_info' => 'False information',
                                'scam' => 'Scam or fraud',
                                'copyright' => 'Copyright violation',
                                'other' => 'Other',
                                ];
                                @endphp

                                @foreach($postReports as $report)
                                @php
                                $reasonFormatted = $reasonMap[$report->reason ?? 'other'] ?? 'Other';
                                @endphp
                                <span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded mr-1 mb-1">
                                    {{ $reasonFormatted }}
                                </span>
                                @endforeach
                            </td>

                            {{-- Additional Details --}}
                            <td class="bg-gray-100 p-3">
                                @foreach($postReports as $report)
                                @if($report->details)
                                <div class="text-sm text-gray-700 mb-1 line-clamp-3">
                                    • {{ $report->details }}
                                </div>
                                @endif
                                @endforeach
                            </td>

                            {{-- Total Reports --}}
                            <td class="bg-gray-100 p-3 text-center text-sm font-semibold">
                                {{ $postReports->count() }} report{{ $postReports->count() > 1 ? 's' : '' }}
                            </td>

                            {{-- Actions --}}
                            <td class="bg-gray-100 p-3">
                                <div class="flex gap-2 justify-center">
                                    {{-- Delete Report --}}
                                    <form method="POST" action="{{ route('admin.reports.delete', $postReports->first()->id) }}"
                                        onsubmit="return confirm('Delete this report? The post will remain.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs px-3 py-2 rounded font-medium cursor-pointer">
                                            Delete Record
                                        </button>
                                    </form>

                                    {{-- Delete Post --}}
                                    @if($postReports->first()->post && !$postReports->first()->post->trashed())
                                    <form method="POST" action="{{ route('admin.posts.delete', $postReports->first()->id) }}"
                                        onsubmit="return confirm('Delete this post permanently? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-2 rounded font-medium cursor-pointer">
                                            Delete Post
                                        </button>
                                    </form>
                                    @else
                                    <button disabled
                                        class="bg-gray-300 text-gray-500 text-xs px-3 py-2 rounded font-medium cursor-not-allowed">
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

{{-- Simple Search Functionality (Content only) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('tbody tr');

        searchInput?.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            tableRows.forEach(row => {
                const contentTd = row.querySelectorAll('td')[3]; // 4th column = Content
                if (!contentTd) return;

                const contentText = contentTd.textContent.toLowerCase();
                row.style.display = contentText.includes(searchTerm) ? '' : 'none';
            });
        });
    });
</script>

@endsection