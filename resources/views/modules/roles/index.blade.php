<x-app-layout>
    <div class="">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">🛡️ Role Management</h1>
                            <p class="text-gray-600 mt-1">Manage system roles and permissions</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <button onclick="exportRoles()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                📤 Export CSV
                            </button>
                            <a href="{{ route('dashboard_create.roles') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                ➕ Add Role
                            </a>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Role Name</label>
                                <input type="text" name="role_name" value="{{ request('role_name') }}" placeholder="Filter by role name" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Description</label>
                                <input type="text" name="role_desc" value="{{ request('role_desc') }}" placeholder="Filter by description" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Level</label>
                                <select name="role_lvl" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">All Levels</option>
                                    <option value="0" {{ request('role_lvl') == '0' ? 'selected' : '' }}>Level 0</option>
                                    <option value="1" {{ request('role_lvl') == '1' ? 'selected' : '' }}>Level 1</option>
                                    <option value="2" {{ request('role_lvl') == '2' ? 'selected' : '' }}>Level 2</option>
                                    <option value="3" {{ request('role_lvl') == '3' ? 'selected' : '' }}>Level 3</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Created Date</label>
                                <input type="date" name="created_date" value="{{ request('created_date') }}" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="flex space-x-2">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">🔍 Filter</button>
                                <a href="{{ route('dashboard_list.roles') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition duration-200">Clear</a>
                            </div>
                        </form>
                    </div>

                    <!-- Bulk Actions Bar -->
                    <div id="bulkActions" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-between">
                            <span class="text-blue-800 font-medium">Selected: <span id="selectedCount">0</span> roles</span>
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
                        <div>
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <input type="checkbox" id="selectAll" class="rounded" onchange="toggleAll()">
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($data as $role)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="checkbox" name="selected[]" value="{{ $role->id }}" class="rounded role-checkbox">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $role->role_name }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">{{ Str::limit($role->role_desc, 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @if($role->role_lvl == 0) bg-gray-100 text-gray-800
                                                    @elseif($role->role_lvl == 1) bg-blue-100 text-blue-800
                                                    @elseif($role->role_lvl == 2) bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    Level {{ $role->role_lvl }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $role->created_at ? \Carbon\Carbon::parse($role->created_at)->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('dashboard_add_users.roles', ['id' => $role->id, 'lvl' => $role->role_lvl, 'role_name' => $role->role_name]) }}" class="text-green-600 hover:text-green-900">👥</a>
                                                    <a href="{{ route('dashboard_edit.roles', $role->id) }}" class="text-yellow-600 hover:text-yellow-900">✏️</a>
                                                    <form action="{{ route('dashboard_destroy.roles') }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="id" value="{{ $role->id }}">
                                                        <button type="submit" class="text-red-600 hover:text-red-900">🗑️</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center">
                                                <div class="text-gray-500">
                                                    <div class="text-6xl mb-4">🛡️</div>
                                                    <h3 class="text-lg font-medium mb-2">No roles found</h3>
                                                    <p class="text-sm">Get started by adding your first role.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="lg:hidden space-y-4">
                        @forelse($data as $role)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $role->role_name }}</div>
                                        <div class="text-sm text-gray-500 mt-1">{{ $role->role_desc }}</div>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        @if($role->role_lvl == 0) bg-gray-100 text-gray-800
                                        @elseif($role->role_lvl == 1) bg-blue-100 text-blue-800
                                        @elseif($role->role_lvl == 2) bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        Level {{ $role->role_lvl }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="text-xs text-gray-500">
                                        {{ $role->created_at ? \Carbon\Carbon::parse($role->created_at)->format('M d, Y') : 'N/A' }}
                                    </div>
                                    <div class="flex space-x-3">
                                        <a href="{{ route('dashboard_add_users.roles', ['id' => $role->id, 'lvl' => $role->role_lvl, 'role_name' => $role->role_name]) }}" class="text-green-600">👥</a>
                                        <a href="{{ route('dashboard_edit.roles', $role->id) }}" class="text-yellow-600">✏️</a>
                                        <form action="{{ route('dashboard_destroy.roles') }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $role->id }}">
                                            <button type="submit" class="text-red-600">🗑️</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="text-gray-500">
                                    <div class="text-6xl mb-4">🛡️</div>
                                    <h3 class="text-lg font-medium mb-2">No roles found</h3>
                                    <p class="text-sm mb-4">Get started by adding your first role.</p>
                                    <a href="{{ route('dashboard_create.roles') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                        Add First Role
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($data->hasPages())
                        <div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                            <div class="text-sm text-gray-700">
                                Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} results
                            </div>
                            <div class="flex justify-center">
                                {{ $data->appends(request()->query())->links('pagination::tailwind') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
    // Bulk Actions
    function toggleAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.role-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBulkActions();
    }

    function updateBulkActions() {
        const selected = document.querySelectorAll('.role-checkbox:checked');
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
    function exportRoles() {
        const params = new URLSearchParams(window.location.search);
        params.set('export', 'csv');
        window.location.href = '{{ route("dashboard_list.roles") }}?' + params.toString();
    }

    function bulkExport() {
        const selected = Array.from(document.querySelectorAll('.role-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) return;
        
        const params = new URLSearchParams();
        params.set('export_selected', selected.join(','));
        window.location.href = '{{ route("dashboard_list.roles") }}?' + params.toString();
    }

    function bulkDelete() {
        const selected = Array.from(document.querySelectorAll('.role-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) return;
        
        if (confirm(`Delete ${selected.length} selected roles? This cannot be undone.`)) {
            const params = new URLSearchParams();
            params.set('bulk_delete', selected.join(','));
            window.location.href = '{{ route("dashboard_list.roles") }}?' + params.toString();
        }
    }

    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.role-checkbox').forEach(cb => {
            cb.addEventListener('change', updateBulkActions);
        });
    });
    </script>
</x-app-layout>