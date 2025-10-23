<x-app-layout>
    <div class="">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">💰 Expense Tracker</h1>
                            <p class="text-gray-600 mt-1">Manage and track your organization expenses</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('expense.v1.dashboard') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                📊 Dashboard
                            </a>
                            <a href="{{ route('expense_type.v1.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                📋 Expense Types
                            </a>
                            <button onclick="exportExpenses()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                📤 Export CSV
                            </button>
                            <a href="{{ route('expense.v1.new') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                ➕ Add Expense
                            </a>
                        </div>
                    </div>

                    <!-- Advanced Filters -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">🔍 Search & Filter Expenses</h3>
                            <button type="button" onclick="toggleAdvancedFilters()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <span id="advancedToggleText">Show Advanced</span> ▼
                            </button>
                        </div>
                        
                        <form method="GET" id="filterForm">
                            <!-- Basic Filters -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Search</label>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search description, type..." class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Expense Type</label>
                                    <select name="expense_type_id" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">All Types</option>
                                        @php
                                            $expenseTypes = \App\Models\ExpenseType::all();
                                        @endphp
                                        @foreach($expenseTypes as $type)
                                            <option value="{{ $type->id }}" {{ request('expense_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Date Range</label>
                                    <select name="date_range" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">All Dates</option>
                                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>📅 Today</option>
                                        <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>📅 This Week</option>
                                        <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>📅 This Month</option>
                                        <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>📅 Last Month</option>
                                        <option value="this_year" {{ request('date_range') == 'this_year' ? 'selected' : '' }}>📅 This Year</option>
                                    </select>
                                </div>
                                <div class="flex space-x-2 items-end">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 flex-1">🔍 Search</button>
                                    <a href="{{ route('expense.v1.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition duration-200">Clear</a>
                                </div>
                            </div>
                            
                            <!-- Advanced Filters -->
                            <div id="advancedFilters" class="hidden border-t pt-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Custom Date From</label>
                                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Custom Date To</label>
                                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Amount Range</label>
                                        <div class="flex space-x-1">
                                            <input type="number" name="amount_min" value="{{ request('amount_min') }}" placeholder="Min" class="w-1/2 text-sm border border-gray-300 rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <input type="number" name="amount_max" value="{{ request('amount_max') }}" placeholder="Max" class="w-1/2 text-sm border border-gray-300 rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Sort By</label>
                                        <select name="sort_by" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="date_desc" {{ request('sort_by') == 'date_desc' ? 'selected' : '' }}>Date (Newest First)</option>
                                            <option value="date_asc" {{ request('sort_by') == 'date_asc' ? 'selected' : '' }}>Date (Oldest First)</option>
                                            <option value="amount_desc" {{ request('sort_by') == 'amount_desc' ? 'selected' : '' }}>Amount (High to Low)</option>
                                            <option value="amount_asc" {{ request('sort_by') == 'amount_asc' ? 'selected' : '' }}>Amount (Low to High)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Bulk Actions Bar -->
                    <div id="bulkActions" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-between">
                            <span class="text-blue-800 font-medium">Selected: <span id="selectedCount">0</span> expenses</span>
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($data as $expense)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="checkbox" name="selected[]" value="{{ $expense->id }}" class="rounded expense-checkbox">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $expense->type ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">{{ Str::limit($expense->description ?? 'N/A', 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">${{ number_format($expense->amount ?? 0, 2) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $expense->expense_date ? \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('expense.v1.show', $expense->id) }}" class="text-blue-600 hover:text-blue-900">👁️</a>
                                                    <a href="{{ route('expense.v1.edit', $expense->id) }}" class="text-yellow-600 hover:text-yellow-900">✏️</a>
                                                    <form action="{{ route('expense.v2.delete', $expense->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                                        @csrf
                                                        <button type="submit" class="text-red-600 hover:text-red-900">🗑️</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center">
                                                <div class="text-gray-500">
                                                    <div class="text-6xl mb-4">📊</div>
                                                    <h3 class="text-lg font-medium mb-2">No expenses found</h3>
                                                    <p class="text-sm">Get started by adding your first expense.</p>
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
                        @forelse($data as $expense)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $expense->type ?? 'N/A' }}
                                    </span>
                                    <div class="text-lg font-bold text-green-600">
                                        ${{ number_format($expense->amount ?? 0, 2) }}
                                    </div>
                                </div>
                                <div class="text-sm text-gray-900 mb-2">
                                    {{ $expense->description ?? 'N/A' }}
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="text-xs text-gray-500">
                                        {{ $expense->expense_date ? \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') : 'N/A' }}
                                    </div>
                                    <div class="flex space-x-3">
                                        <a href="{{ route('expense.v1.show', $expense->id) }}" class="text-blue-600">👁️</a>
                                        <a href="{{ route('expense.v1.edit', $expense->id) }}" class="text-yellow-600">✏️</a>
                                        <form action="{{ route('expense.v2.delete', $expense->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                            @csrf
                                            <button type="submit" class="text-red-600">🗑️</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="text-gray-500">
                                    <div class="text-6xl mb-4">📊</div>
                                    <h3 class="text-lg font-medium mb-2">No expenses found</h3>
                                    <p class="text-sm mb-4">Get started by adding your first expense.</p>
                                    <a href="{{ route('expense.v1.new') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                        Add First Expense
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
    // Advanced Filters Toggle
    function toggleAdvancedFilters() {
        const advancedFilters = document.getElementById('advancedFilters');
        const toggleText = document.getElementById('advancedToggleText');
        
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
        const checkboxes = document.querySelectorAll('.expense-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBulkActions();
    }

    function updateBulkActions() {
        const selected = document.querySelectorAll('.expense-checkbox:checked');
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
    function exportExpenses() {
        const params = new URLSearchParams(window.location.search);
        params.set('export', 'csv');
        window.location.href = '{{ route("expense.v1.index") }}?' + params.toString();
    }

    function bulkExport() {
        const selected = Array.from(document.querySelectorAll('.expense-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) return;
        
        const params = new URLSearchParams();
        params.set('export_selected', selected.join(','));
        window.location.href = '{{ route("expense.v1.index") }}?' + params.toString();
    }

    function bulkDelete() {
        const selected = Array.from(document.querySelectorAll('.expense-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) return;
        
        if (confirm(`Delete ${selected.length} selected expenses? This cannot be undone.`)) {
            const params = new URLSearchParams();
            params.set('bulk_delete', selected.join(','));
            window.location.href = '{{ route("expense.v1.index") }}?' + params.toString();
        }
    }

    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.expense-checkbox').forEach(cb => {
            cb.addEventListener('change', updateBulkActions);
        });
    });
    </script>
</x-app-layout>