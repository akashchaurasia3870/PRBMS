<x-app-layout>
    <div class="">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">📋 Expense Audit Logs</h1>
                            <p class="text-gray-600 mt-1">Track all expense-related activities</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('expense.v1.dashboard') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg">
                                📊 Dashboard
                            </a>
                            <a href="{{ route('expense.v1.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                                ← Back to Expenses
                            </a>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Action</label>
                                <select name="action" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2">
                                    <option value="">All Actions</option>
                                    <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                                    <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                                    <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Date From</label>
                                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Date To</label>
                                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2">
                            </div>
                            <div class="flex space-x-2">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">🔍 Filter</button>
                                <a href="{{ route('expense.audit.logs') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg text-sm">Clear</a>
                            </div>
                        </form>
                    </div>

                    <!-- Audit Logs Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date/Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Record</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Details</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $log->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $log->user->name ?? 'System' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($log->action == 'created')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    ✅ Created
                                                </span>
                                            @elseif($log->action == 'updated')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    ✏️ Updated
                                                </span>
                                            @elseif($log->action == 'deleted')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    🗑️ Deleted
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            @if($log->action == 'created' && isset($log->changes['type']))
                                                Created: {{ $log->changes['type'] }} - ${{ number_format($log->changes['amount'] ?? 0, 2) }}
                                            @elseif($log->action == 'updated' && isset($log->changes['new']))
                                                Updated: {{ $log->changes['new']['type'] ?? 'N/A' }}
                                            @elseif($log->action == 'deleted' && isset($log->changes['type']))
                                                Deleted: {{ $log->changes['type'] }} - ${{ number_format($log->changes['amount'] ?? 0, 2) }}
                                            @else
                                                {{ $log->remarks }}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="text-gray-500">
                                                <div class="text-6xl mb-4">📋</div>
                                                <h3 class="text-lg font-medium mb-2">No audit logs found</h3>
                                                <p class="text-sm">No expense activities recorded yet.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($logs->hasPages())
                        <div class="mt-6">
                            {{ $logs->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>