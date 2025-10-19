<x-app-layout>
    <div class="mx-auto bg-white rounded-2xl shadow-lg p-6 h-full overflow-y-auto no-scrollbar">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">📝 Inventory Logs</h2>
        <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <form method="GET" class="flex flex-wrap gap-2 items-center bg-gray-50 p-4 rounded shadow">
                <input type="text" name="auditable_id" value="{{ request('auditable_id') }}" placeholder="Auditable ID" class="border rounded px-2 py-1" />
                <input type="text" name="auditable_type" value="{{ request('auditable_type') }}" placeholder="Auditable Type" class="border rounded px-2 py-1" />
                <input type="text" name="user_id" value="{{ request('user_id') }}" placeholder="User ID" class="border rounded px-2 py-1" />
                <input type="text" name="action" value="{{ request('action') }}" placeholder="Action" class="border rounded px-2 py-1" />
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Filter</button>
                <a href="{{ route('inventory.logs', $inventory->id) }}" class="bg-gray-300 text-gray-800 px-3 py-1 rounded hover:bg-gray-400 transition">Reset</a>
            </form>
            <a href="{{ url()->previous() }}" class="inline-block bg-gray-300 text-gray-800 px-3 py-1 rounded hover:bg-gray-400 transition">&larr; Go Back</a>
        </div>

        @if($logs->count())
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden" id="logs-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Auditable ID</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Auditable Type</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">User ID</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Action</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Changes</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Remarks</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Created At</th>
                        </tr>
                    </thead>
                    <tbody id="logs-tbody">
                        @foreach($logs as $log)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="px-5 py-4 text-gray-800 font-medium">{{ $log->auditable_id }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $log->auditable_type }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $log->user_id }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $log->action }}</td>
                                <td class="px-5 py-4 text-gray-700 break-all">
                                    <pre class="whitespace-pre-wrap text-xs">{{ is_array($log->changes) ? json_encode($log->changes, JSON_PRETTY_PRINT) : $log->changes }}</pre>
                                </td>
                                <td class="px-5 py-4 text-gray-700">{{ $log->remarks }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $log->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $logs->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center text-gray-500 py-10">
                No logs found.
            </div>
        @endif
    </div>
</x-app-layout>