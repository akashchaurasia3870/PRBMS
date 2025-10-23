<x-app-layout>
    <div class="">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">📦 Inventory Management</h1>
                            <p class="text-gray-600 mt-1">Manage and track your inventory items</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('inventory.dashboard') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                📊 Dashboard
                            </a>
                            <button onclick="exportInventory()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                📤 Export CSV
                            </button>
                            <a href="{{ route('inventory.v1.new') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                ➕ Add Item
                            </a>
                        </div>
                    </div>

                    <!-- Advanced Filters -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">🔍 Search & Filter</h3>
                            <button type="button" onclick="toggleAdvancedFilters()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <span id="advancedToggleText">Show Advanced</span> ▼
                            </button>
                        </div>
                        
                        <form method="GET" id="filterForm">
                            <!-- Basic Filters -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Search</label>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, code, barcode..." class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Category</label>
                                    <select name="category_id" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">All Categories</option>
                                        @php
                                            $categories = \App\Models\Category::active()->get();
                                        @endphp
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->icon }} {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Stock Status</label>
                                    <select name="stock_status" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">All Stock Levels</option>
                                        <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>🔴 Low Stock</option>
                                        <option value="normal" {{ request('stock_status') == 'normal' ? 'selected' : '' }}>🟢 Normal Stock</option>
                                        <option value="high" {{ request('stock_status') == 'high' ? 'selected' : '' }}>🔵 High Stock</option>
                                        <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>⚫ Out of Stock</option>
                                    </select>
                                </div>
                                <div class="flex space-x-2 items-end">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 flex-1">🔍 Search</button>
                                    <a href="{{ route('inventory.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition duration-200">Clear</a>
                                </div>
                            </div>
                            
                            <!-- Advanced Filters -->
                            <div id="advancedFilters" class="hidden border-t pt-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Barcode</label>
                                        <div class="flex items-center space-x-1">
                                            <input type="text" name="barcode" value="{{ request('barcode') }}" placeholder="Scan or type barcode" class="flex-1 text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <button type="button" onclick="startQuickBarcodeScanner()" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-2 rounded text-xs" title="Scan Barcode">📷</button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Location</label>
                                        <input type="text" name="location" value="{{ request('location') }}" placeholder="Filter by location" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Supplier</label>
                                        <input type="text" name="supplier" value="{{ request('supplier') }}" placeholder="Filter by supplier" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Price Range</label>
                                        <div class="flex space-x-1">
                                            <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="Min" class="w-1/2 text-sm border border-gray-300 rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="Max" class="w-1/2 text-sm border border-gray-300 rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Quantity Range</label>
                                        <div class="flex space-x-1">
                                            <input type="number" name="qty_min" value="{{ request('qty_min') }}" placeholder="Min" class="w-1/2 text-sm border border-gray-300 rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <input type="number" name="qty_max" value="{{ request('qty_max') }}" placeholder="Max" class="w-1/2 text-sm border border-gray-300 rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Expiry Status</label>
                                        <select name="expiry_status" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">All Items</option>
                                            <option value="expiring_soon" {{ request('expiry_status') == 'expiring_soon' ? 'selected' : '' }}>⚠️ Expiring Soon</option>
                                            <option value="expired" {{ request('expiry_status') == 'expired' ? 'selected' : '' }}>🔴 Expired</option>
                                            <option value="no_expiry" {{ request('expiry_status') == 'no_expiry' ? 'selected' : '' }}>♾️ No Expiry Date</option>
                                        </select>
                                    </div>
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
                        <div>
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <input type="checkbox" id="selectAll" class="rounded" onchange="toggleAll()">
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($data as $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="checkbox" name="selected[]" value="{{ $item->id }}" class="rounded inventory-checkbox">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $item->item_code ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">{{ Str::limit($item->item_name ?? 'N/A', 30) }}</div>
                                                @if($item->item_img_path)
                                                    <img src="{{ asset('storage/' . $item->item_img_path) }}" width="40" class="rounded shadow mt-1">
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    {{ $item->category->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->item_qty > 10 ? 'bg-green-100 text-green-800' : ($item->item_qty > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ $item->item_qty ?? 0 }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">${{ number_format($item->item_price ?? 0, 2) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <button onclick="openStockModal('in', {{ $item->id }}, '{{ $item->item_name }}')" class="text-green-600 hover:text-green-900" title="Stock In">➕</button>
                                                    <button onclick="openStockModal('out', {{ $item->id }}, '{{ $item->item_name }}', {{ $item->item_qty }})" class="text-orange-600 hover:text-orange-900" title="Stock Out">➖</button>
                                                    <a href="{{ route('inventory.show', $item->id) }}" class="text-blue-600 hover:text-blue-900" title="View">👁️</a>
                                                    <a href="{{ route('inventory.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">✏️</a>
                                                    <form action="{{ route('inventory.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
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
                                                    <div class="text-6xl mb-4">📦</div>
                                                    <h3 class="text-lg font-medium mb-2">No inventory items found</h3>
                                                    <p class="text-sm">Get started by adding your first inventory item.</p>
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
                        @forelse($data as $item)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center space-x-3">
                                        @if($item->item_img_path)
                                            <img src="{{ asset('storage/' . $item->item_img_path) }}" width="50" class="rounded shadow">
                                        @endif
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $item->item_code ?? 'N/A' }}
                                            </span>
                                            <div class="text-sm font-medium text-gray-900 mt-1">{{ $item->item_name }}</div>
                                        </div>
                                    </div>
                                    <div class="text-lg font-bold text-green-600">
                                        ${{ number_format($item->item_price ?? 0, 2) }}
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $item->category->name ?? 'N/A' }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->item_qty > 10 ? 'bg-green-100 text-green-800' : ($item->item_qty > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        Qty: {{ $item->item_qty ?? 0 }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="text-xs text-gray-500">
                                        Total Value: ${{ number_format(($item->item_price ?? 0) * ($item->item_qty ?? 0), 2) }}
                                    </div>
                                    <div class="flex space-x-3">
                                        <button onclick="openStockModal('in', {{ $item->id }}, '{{ $item->item_name }}')" class="text-green-600">➕</button>
                                        <button onclick="openStockModal('out', {{ $item->id }}, '{{ $item->item_name }}', {{ $item->item_qty }})" class="text-orange-600">➖</button>
                                        <a href="{{ route('inventory.show', $item->id) }}" class="text-blue-600">👁️</a>
                                        <a href="{{ route('inventory.edit', $item->id) }}" class="text-yellow-600">✏️</a>
                                        <form action="{{ route('inventory.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600">🗑️</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="text-gray-500">
                                    <div class="text-6xl mb-4">📦</div>
                                    <h3 class="text-lg font-medium mb-2">No inventory items found</h3>
                                    <p class="text-sm mb-4">Get started by adding your first inventory item.</p>
                                    <a href="{{ route('inventory.v1.new') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                        Add First Item
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

    <!-- Stock Modal -->
    <div id="stockModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center">
        <div class="bg-white rounded shadow-lg w-full max-w-md mx-auto">
            <form id="stockForm" method="POST">
                @csrf
                <div class="flex justify-between items-center px-6 py-4 border-b">
                    <h5 id="modalTitle" class="text-lg font-semibold"></h5>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeStockModal()">&times;</button>
                </div>
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1" id="quantityLabel">Quantity</label>
                        <input type="number" min="1" name="quantity" id="quantityInput" class="w-full border rounded px-3 py-2" required placeholder="Enter quantity">
                    </div>
                </div>
                <div class="flex justify-end space-x-2 px-6 py-4 border-t">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300" onclick="closeStockModal()">Cancel</button>
                    <button id="submitBtn" class="px-4 py-2 text-white rounded" type="submit">Submit</button>
                </div>
            </form>
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
    
    // Barcode Scanner Support
    document.addEventListener('DOMContentLoaded', function() {
        const barcodeInput = document.querySelector('input[name="barcode"]');
        if (barcodeInput) {
            barcodeInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    document.getElementById('filterForm').submit();
                }
            });
        }
    });
    
    // Bulk Actions
    function toggleAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.inventory-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBulkActions();
    }

    function updateBulkActions() {
        const selected = document.querySelectorAll('.inventory-checkbox:checked');
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
    function exportInventory() {
        const params = new URLSearchParams(window.location.search);
        params.set('export', 'csv');
        window.location.href = '{{ route("inventory.index") }}?' + params.toString();
    }

    function bulkExport() {
        const selected = Array.from(document.querySelectorAll('.inventory-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) return;
        
        const params = new URLSearchParams();
        params.set('export_selected', selected.join(','));
        window.location.href = '{{ route("inventory.index") }}?' + params.toString();
    }

    function bulkDelete() {
        const selected = Array.from(document.querySelectorAll('.inventory-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) return;
        
        if (confirm(`Delete ${selected.length} selected items? This cannot be undone.`)) {
            const params = new URLSearchParams();
            params.set('bulk_delete', selected.join(','));
            window.location.href = '{{ route("inventory.index") }}?' + params.toString();
        }
    }

    // Stock Modal Functions
    function openStockModal(type, itemId, itemName, currentQty = null) {
        const modal = document.getElementById('stockModal');
        const form = document.getElementById('stockForm');
        const title = document.getElementById('modalTitle');
        const quantityLabel = document.getElementById('quantityLabel');
        const quantityInput = document.getElementById('quantityInput');
        const submitBtn = document.getElementById('submitBtn');
        
        if (type === 'in') {
            form.action = `/dashboard/inventory/${itemId}/stock-in`;
            title.textContent = `➕ Stock In: ${itemName}`;
            quantityLabel.textContent = 'Quantity to Add';
            quantityInput.removeAttribute('max');
            submitBtn.textContent = 'Add Stock';
            submitBtn.className = 'px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded';
        } else {
            form.action = `/dashboard/inventory/${itemId}/stock-out`;
            title.textContent = `➖ Stock Out: ${itemName}`;
            quantityLabel.textContent = 'Quantity to Remove';
            quantityInput.setAttribute('max', currentQty);
            submitBtn.textContent = 'Remove Stock';
            submitBtn.className = 'px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded';
        }
        
        quantityInput.value = '';
        modal.classList.remove('hidden');
    }

    function closeStockModal() {
        document.getElementById('stockModal').classList.add('hidden');
    }

    // Quick Barcode Scanner for Filter
    function startQuickBarcodeScanner() {
        const barcodeInput = document.querySelector('input[name="barcode"]');
        
        // Simple simulation - in real implementation, use camera
        const simulatedBarcode = prompt('Enter barcode or use camera scanner:');
        if (simulatedBarcode) {
            barcodeInput.value = simulatedBarcode;
            document.getElementById('filterForm').submit();
        }
    }
    
    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.inventory-checkbox').forEach(cb => {
            cb.addEventListener('change', updateBulkActions);
        });
        
        // Auto-submit on barcode scan (Enter key)
        const barcodeInput = document.querySelector('input[name="barcode"]');
        if (barcodeInput) {
            barcodeInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    document.getElementById('filterForm').submit();
                }
            });
        }
    });
    </script>
</x-app-layout>
