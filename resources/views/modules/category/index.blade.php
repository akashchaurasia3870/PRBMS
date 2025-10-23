<x-app-layout>
    <div class="">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">🏷️ Category Management</h1>
                            <p class="text-gray-600 mt-1">Manage inventory categories</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <button onclick="exportCategories()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                📤 Export CSV
                            </button>
                            <a href="{{ route('category.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                ➕ Add Category
                            </a>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Name</label>
                                <input type="text" name="name" value="{{ request('name') }}" placeholder="Filter by name" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Code</label>
                                <input type="text" name="code" value="{{ request('code') }}" placeholder="Filter by code" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Description</label>
                                <input type="text" name="description" value="{{ request('description') }}" placeholder="Filter by description" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="flex space-x-2">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">🔍 Filter</button>
                                <a href="{{ route('category.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition duration-200">Clear</a>
                            </div>
                        </form>
                    </div>

                    <!-- Bulk Actions Bar -->
                    <div id="bulkActions" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-between">
                            <span class="text-blue-800 font-medium">Selected: <span id="selectedCount">0</span> categories</span>
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @php $categories = $data ?? $categories ?? collect(); @endphp
                                    @forelse($categories as $cat)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="checkbox" name="selected[]" value="{{ $cat->id }}" class="rounded category-checkbox">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $cat->name ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $cat->code ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">{{ Str::limit($cat->description ?? 'N/A', 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $cat->inventories->count() ?? 0 }} items
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('category.show', $cat->id) }}" class="text-blue-600 hover:text-blue-900" title="View">👁️</a>
                                                    <a href="{{ route('category.edit', $cat->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">✏️</a>
                                                    <form action="{{ route('category.destroy', $cat->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">🗑️</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center">
                                                <div class="text-gray-500">
                                                    <div class="text-6xl mb-4">🏷️</div>
                                                    <h3 class="text-lg font-medium mb-2">No categories found</h3>
                                                    <p class="text-sm">Get started by adding your first category.</p>
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
                        @forelse($categories as $cat)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $cat->name }}</div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                            {{ $cat->code }}
                                        </span>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $cat->inventories->count() }} items
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500 mb-2">
                                    {{ Str::limit($cat->description ?? 'No description', 60) }}
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('category.show', $cat->id) }}" class="text-blue-600">👁️</a>
                                    <a href="{{ route('category.edit', $cat->id) }}" class="text-yellow-600">✏️</a>
                                    <form action="{{ route('category.destroy', $cat->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600">🗑️</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="text-gray-500">
                                    <div class="text-6xl mb-4">🏷️</div>
                                    <h3 class="text-lg font-medium mb-2">No categories found</h3>
                                    <p class="text-sm mb-4">Get started by adding your first category.</p>
                                    <a href="{{ route('category.create') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                        Add First Category
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($categories->hasPages())
                        <div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                            <div class="text-sm text-gray-700">
                                Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} results
                            </div>
                            <div class="flex justify-center">
                                {{ $categories->appends(request()->query())->links('pagination::tailwind') }}
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
        const checkboxes = document.querySelectorAll('.category-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBulkActions();
    }

    function updateBulkActions() {
        const selected = document.querySelectorAll('.category-checkbox:checked');
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
    function exportCategories() {
        const params = new URLSearchParams(window.location.search);
        params.set('export', 'csv');
        window.location.href = '{{ route("category.index") }}?' + params.toString();
    }

    function bulkExport() {
        const selected = Array.from(document.querySelectorAll('.category-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) return;
        
        const params = new URLSearchParams();
        params.set('export_selected', selected.join(','));
        window.location.href = '{{ route("category.index") }}?' + params.toString();
    }

    function bulkDelete() {
        const selected = Array.from(document.querySelectorAll('.category-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) return;
        
        if (confirm(`Delete ${selected.length} selected categories? This cannot be undone.`)) {
            const params = new URLSearchParams();
            params.set('bulk_delete', selected.join(','));
            window.location.href = '{{ route("category.index") }}?' + params.toString();
        }
    }

    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.category-checkbox').forEach(cb => {
            cb.addEventListener('change', updateBulkActions);
        });
    });
    </script>
</x-app-layout>
