<x-app-layout>
    <div class="">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">📝 Leave Management</h1>
                            <p class="text-gray-600 mt-1">Manage employee leave requests and approvals</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <button onclick="exportLeaves()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                📤 Export CSV
                            </button>
                            <a href="{{ route('dashboard_leave.leave_request_view') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                ➕ Apply Leave
                            </a>
                        </div>
                    </div>

                    <!-- Enhanced Filters -->
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-6 rounded-xl mb-6 border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                🔍 Advanced Search & Filters
                            </h3>
                            <button type="button" onclick="toggleAdvancedFilters()" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                <span id="toggleText">Show Advanced</span>
                            </button>
                        </div>
                        
                        <form method="GET" id="filterForm">
                            <!-- Basic Filters -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-2">🔍 Global Search</label>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user, reason, description..." class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-2">👤 User</label>
                                    <input type="text" name="user" value="{{ request('user') }}" placeholder="Search by user name..." class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-2">📊 Status</label>
                                    <select name="status" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>🟡 Pending</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>🟢 Approved</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>🔴 Rejected</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-2">🏷️ Leave Type</label>
                                    <select name="leave_type" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">All Types</option>
                                        <option value="sick" {{ request('leave_type') == 'sick' ? 'selected' : '' }}>🤒 Sick Leave</option>
                                        <option value="vacation" {{ request('leave_type') == 'vacation' ? 'selected' : '' }}>🏖️ Vacation</option>
                                        <option value="personal" {{ request('leave_type') == 'personal' ? 'selected' : '' }}>👤 Personal</option>
                                        <option value="emergency" {{ request('leave_type') == 'emergency' ? 'selected' : '' }}>🚨 Emergency</option>
                                        <option value="maternity" {{ request('leave_type') == 'maternity' ? 'selected' : '' }}>🤱 Maternity</option>
                                        <option value="paternity" {{ request('leave_type') == 'paternity' ? 'selected' : '' }}>👨‍👶 Paternity</option>
                                        <option value="bereavement" {{ request('leave_type') == 'bereavement' ? 'selected' : '' }}>⚰️ Bereavement</option>
                                        <option value="other" {{ request('leave_type') == 'other' ? 'selected' : '' }}>📝 Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Advanced Filters -->
                            <div id="advancedFilters" class="hidden">
                                <div class="border-t border-gray-200 pt-4 mt-4">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-2">📅 From Date</label>
                                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-2">📅 To Date</label>
                                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-2">📊 Min Days</label>
                                            <input type="number" name="days_min" value="{{ request('days_min') }}" placeholder="Min days" min="1" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-2">📊 Max Days</label>
                                            <input type="number" name="days_max" value="{{ request('days_max') }}" placeholder="Max days" min="1" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-3 items-center">
                                <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition duration-200 shadow-sm">
                                    🔍 Search
                                </button>
                                <a href="{{ route('dashboard_leave.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition duration-200 border border-gray-300">
                                    ❌ Clear All
                                </a>
                                <div class="text-xs text-gray-500 ml-auto">
                                    {{ $leaves->total() }} results found
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Bulk Actions Bar -->
                    <div id="bulkActions" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-between">
                            <span class="text-blue-800 font-medium">Selected: <span id="selectedCount">0</span> items</span>
                            <div class="flex space-x-2">
                                <button onclick="bulkDelete()" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    🗑️ Delete Selected
                                </button>
                                <button onclick="bulkExport()" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                    📤 Export Selected
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Desktop Table -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" id="selectAll" class="rounded" onchange="toggleAll()">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leave Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($leaves as $leave)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="selected[]" value="{{ $leave->id }}" class="rounded leave-checkbox">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $leave->name ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">ID: {{ $leave->user_id }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $typeColors = [
                                                    'sick' => 'bg-red-100 text-red-800',
                                                    'vacation' => 'bg-blue-100 text-blue-800',
                                                    'personal' => 'bg-purple-100 text-purple-800',
                                                    'emergency' => 'bg-orange-100 text-orange-800',
                                                    'maternity' => 'bg-pink-100 text-pink-800',
                                                    'paternity' => 'bg-indigo-100 text-indigo-800',
                                                    'bereavement' => 'bg-gray-100 text-gray-800',
                                                    'other' => 'bg-yellow-100 text-yellow-800'
                                                ];
                                                $typeIcons = [
                                                    'sick' => '🤒',
                                                    'vacation' => '🏖️',
                                                    'personal' => '👤',
                                                    'emergency' => '🚨',
                                                    'maternity' => '🤱',
                                                    'paternity' => '👨‍👶',
                                                    'bereavement' => '⚰️',
                                                    'other' => '📝'
                                                ];
                                                $colorClass = $typeColors[$leave->leave_type] ?? 'bg-gray-100 text-gray-800';
                                                $icon = $typeIcons[$leave->leave_type] ?? '📝';
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                                {{ $icon }} {{ ucfirst($leave->leave_type ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }} days</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($leave->status === 'approved')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    ✅ Approved
                                                </span>
                                            @elseif($leave->status === 'rejected')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    ❌ Rejected
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    ⏳ Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($leave->created_at)->format('M d, Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($leave->created_at)->diffForHumans() }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('dashboard_leave.get_leave_info', $leave->id) }}" class="text-blue-600 hover:text-blue-900" title="View">👁️</a>
                                                @if($leave->status === 'pending')
                                                    <form action="{{ route('dashboard_leave.approve_leave_status', $leave->id) }}" method="POST" class="inline" onsubmit="return confirm('Approve this leave?')">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-900" title="Approve">✅</button>
                                                    </form>
                                                    <button onclick="openRejectModal({{ $leave->id }})" class="text-red-600 hover:text-red-900" title="Reject">❌</button>
                                                @endif
                                                <form action="{{ route('dashboard_leave.destroy_leave_info', $leave->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">🗑️</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <div class="text-gray-500">
                                                <div class="text-6xl mb-4">📝</div>
                                                <h3 class="text-lg font-medium mb-2">No leave requests found</h3>
                                                <p class="text-sm">Start by applying for leave or adjust your filters.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="lg:hidden space-y-4">
                        @forelse($leaves as $leave)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $leave->name }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $leave->user_id }}</div>
                                    </div>
                                    <div>
                                        @if($leave->status === 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">✅ Approved</span>
                                        @elseif($leave->status === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">❌ Rejected</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">⏳ Pending</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $leave->leave_type }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600 mb-2">
                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                    ({{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }} days)
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="text-xs text-gray-500">
                                        Applied {{ \Carbon\Carbon::parse($leave->created_at)->diffForHumans() }}
                                    </div>
                                    <div class="flex space-x-3">
                                        <a href="{{ route('dashboard_leave.get_leave_info', $leave->id) }}" class="text-blue-600">👁️</a>
                                        @if($leave->status === 'pending')
                                            <form action="{{ route('dashboard_leave.approve_leave_status', $leave->id) }}" method="POST" class="inline" onsubmit="return confirm('Approve?')">
                                                @csrf
                                                <button type="submit" class="text-green-600">✅</button>
                                            </form>
                                            <button onclick="openRejectModal({{ $leave->id }})" class="text-red-600">❌</button>
                                        @endif
                                        <form action="{{ route('dashboard_leave.destroy_leave_info', $leave->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600">🗑️</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="text-gray-500">
                                    <div class="text-6xl mb-4">📝</div>
                                    <h3 class="text-lg font-medium mb-2">No leave requests found</h3>
                                    <p class="text-sm mb-4">Start by applying for leave.</p>
                                    <a href="{{ route('dashboard_leave.leave_request_view') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                        Apply Leave
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($leaves->hasPages())
                        <div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                            <div class="text-sm text-gray-700">
                                Showing {{ $leaves->firstItem() }} to {{ $leaves->lastItem() }} of {{ $leaves->total() }} results
                            </div>
                            <div class="flex justify-center">
                                {{ $leaves->appends(request()->query())->links('pagination::tailwind') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center">
        <div class="bg-white rounded shadow-lg w-full max-w-md mx-auto">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="flex justify-between items-center px-6 py-4 border-b">
                    <h5 class="text-lg font-semibold">Reject Leave Request</h5>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeRejectModal()">&times;</button>
                </div>
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Reason for Rejection</label>
                        <textarea name="reason" id="rejectReason" class="w-full border rounded px-3 py-2" rows="3" required placeholder="Please provide a reason..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 px-6 py-4 border-t">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300" onclick="closeRejectModal()">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Reject</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Advanced Filters Toggle
    function toggleAdvancedFilters() {
        const advancedFilters = document.getElementById('advancedFilters');
        const toggleText = document.getElementById('toggleText');
        
        if (advancedFilters.classList.contains('hidden')) {
            advancedFilters.classList.remove('hidden');
            toggleText.textContent = 'Hide Advanced';
        } else {
            advancedFilters.classList.add('hidden');
            toggleText.textContent = 'Show Advanced';
        }
    }
    
    // Bulk Actions
    function toggleAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.leave-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBulkActions();
    }

    function updateBulkActions() {
        const selected = document.querySelectorAll('.leave-checkbox:checked');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');
        
        if (selected.length > 0) {
            bulkActions.classList.remove('hidden');
            selectedCount.textContent = selected.length;
        } else {
            bulkActions.classList.add('hidden');
        }
    }

    // Export Functions
    function exportLeaves() {
        const params = new URLSearchParams(window.location.search);
        params.set('export', 'csv');
        window.location.href = '{{ route("dashboard_leave.index") }}?' + params.toString();
    }

    function bulkExport() {
        const selected = Array.from(document.querySelectorAll('.leave-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) return;
        
        const params = new URLSearchParams();
        params.set('export_selected', selected.join(','));
        window.location.href = '{{ route("dashboard_leave.index") }}?' + params.toString();
    }

    function bulkDelete() {
        const selected = Array.from(document.querySelectorAll('.leave-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) return;
        
        if (confirm(`Delete ${selected.length} selected items? This cannot be undone.`)) {
            const params = new URLSearchParams();
            params.set('bulk_delete', selected.join(','));
            window.location.href = '{{ route("dashboard_leave.index") }}?' + params.toString();
        }
    }

    // Reject Modal Functions
    function openRejectModal(leaveId) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = `/dashboard/leave/reject/${leaveId}`;
        document.getElementById('rejectReason').value = '';
        modal.classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.leave-checkbox').forEach(cb => {
            cb.addEventListener('change', updateBulkActions);
        });
    });
    </script>
</x-app-layout>