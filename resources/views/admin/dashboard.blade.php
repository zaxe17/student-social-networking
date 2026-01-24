@extends('layout.app')

@section('page')
<div class="min-h-screen bg-gray-100">
    <nav class="bg-[#770d08] px-10 py-4 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <img src="/img/logo.png" class="w-10 h-10">
            <p class="text-2xl text-white font-bold">ISKOnnect</p>
        </div>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="text-white underline">Logout</button>
        </form>
    </nav>

    <div class="max-w-6xl mx-auto py-10">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex justify-between mb-6">
                <h2 class="text-3xl font-semibold">Admin</h2>
                <input class="bg-gray-200 rounded-full px-4 py-2" placeholder="Search Report">
            </div>

            <table class="w-full border-separate border-spacing-1">
                <thead class="bg-[#770d08] text-white">
                    <tr>
                        <th class="p-3">ID</th>
                        <th class="p-3">Post ID</th>
                        <th class="p-3">Reported by</th>
                        <th class="p-3">Reasons</th>
                        <th class="p-3">Details</th>
                        <th class="p-3">Reported Date</th>
                        <th class="p-3">Updated Time</th>
                    </tr>
                </thead>


                <tbody>
                    @forelse($reports as $r)
                    <tr>
                        <td class="bg-gray-700 text-white p-3 text-center">
                            {{ $r->id }}
                        </td>

                        <td class="bg-gray-400 p-3 text-center">
                            {{ $r->post_id }}
                        </td>

                        <td class="bg-gray-400 p-3 text-center">
                            {{ $r->reported_by }}
                        </td>

                        <td class="bg-gray-400 p-3 text-center">
                            {{ $r->reason ?? '-' }}
                        </td>

                        <td class="bg-gray-400 p-3 text-center">
                            {{ $r->details ?? '-' }}
                        </td>

                        <td class="bg-gray-400 p-3 text-center">
                            {{ $r->created_at?->format('m/d/Y') ?? '-' }}
                        </td>

                        <td class="bg-gray-400 p-3 text-center">
                            {{ $r->updated_at?->format('m/d/Y H:i') ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-gray-500">
                            No reports found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection