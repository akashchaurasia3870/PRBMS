<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">📊 Inventory Dashboard</h1>
                        <p class="text-gray-600 mt-1">Overview of your inventory management</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('inventory.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                            📋 View All Items
                        </a>
                        <a href="{{ route('inventory.v1.new') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                            ➕ Add Item
                        </a>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="text-blue-600 text-3xl mr-4">💰</div>
                            <div>
                                <p class="text-sm text-blue-600 font-medium">Total Value</p>
                                <p class="text-2xl font-bold text-blue-800">${{ number_format($data['total_value'] ?? 0, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="text-green-600 text-3xl mr-4">📦</div>
                            <div>
                                <p class="text-sm text-green-600 font-medium">Total Items</p>
                                <p class="text-2xl font-bold text-green-800">{{ $data['item_count'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="text-yellow-600 text-3xl mr-4">⚠️</div>
                            <div>
                                <p class="text-sm text-yellow-600 font-medium">Low Stock</p>
                                <p class="text-2xl font-bold text-yellow-800">{{ count($data['low_stock_items'] ?? []) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="text-purple-600 text-3xl mr-4">🏷️</div>
                            <div>
                                <p class="text-sm text-purple-600 font-medium">Categories</p>
                                <p class="text-2xl font-bold text-purple-800">{{ count($data['inventory_by_category'] ?? []) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Items -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">🕒 Recent Items</h3>
                        @if(isset($data['recent_items']) && $data['recent_items']->count() > 0)
                            <div class="space-y-3">
                                @foreach($data['recent_items'] as $item)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            @if($item->item_img_path)
                                                <img src="{{ asset('storage/' . $item->item_img_path) }}" width="40" class="rounded shadow">
                                            @else
                                                <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">📦</div>
                                            @endif
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ Str::limit($item->item_name, 25) }}</p>
                                                <p class="text-xs text-gray-500">{{ $item->item_code }} • {{ $item->category->name ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-lg font-bold text-green-600">${{ number_format($item->item_price, 2) }}</span>
                                            <p class="text-xs text-gray-500">Qty: {{ $item->item_qty }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('inventory.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View All Items →
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-gray-400 text-4xl mb-2">📦</div>
                                <p class="text-gray-500">No items recorded yet</p>
                                <a href="{{ route('inventory.v1.new') }}" class="inline-block mt-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                                    Add First Item
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Inventory by Category -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">🏷️ Inventory by Category</h3>
                        @if(isset($data['inventory_by_category']) && count($data['inventory_by_category']) > 0)
                            <div class="space-y-3">
                                @foreach($data['inventory_by_category'] as $category)
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-sm font-medium text-gray-600">{{ $category->category_name }}</span>
                                            <p class="text-xs text-gray-500">{{ $category->item_count }} items • {{ $category->total_qty }} total qty</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                                @php $maxValue = collect($data['inventory_by_category'])->max('total_value'); @endphp
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $maxValue > 0 ? ($category->total_value / $maxValue) * 100 : 0 }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">${{ number_format($category->total_value, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No inventory data available</p>
                        @endif
                    </div>
                </div>

                <!-- Low Stock Alert -->
                @if(isset($data['low_stock_items']) && count($data['low_stock_items']) > 0)
                    <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-yellow-800 mb-4">⚠️ Low Stock Alert</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($data['low_stock_items'] as $item)
                                <div class="bg-white border border-yellow-300 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $item->item_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $item->item_code }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $item->item_qty }} left
                                        </span>
                                    </div>
                                    <div class="mt-2 flex space-x-2">
                                        <button onclick="openStockModal('in', {{ $item->id }}, '{{ $item->item_name }}')" class="text-xs bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded">
                                            ➕ Restock
                                        </button>
                                        <a href="{{ route('inventory.edit', $item->id) }}" class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded">
                                            ✏️ Edit
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="mt-8 bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">⚡ Quick Actions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('inventory.v1.new') }}" class="flex items-center justify-center p-4 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition">
                            <div class="text-center">
                                <div class="text-2xl mb-2">➕</div>
                                <p class="text-sm font-medium text-gray-700">Add Item</p>
                            </div>
                        </a>
                        <a href="{{ route('category.v1.index') }}" class="flex items-center justify-center p-4 bg-white border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition">
                            <div class="text-center">
                                <div class="text-2xl mb-2">🏷️</div>
                                <p class="text-sm font-medium text-gray-700">Categories</p>
                            </div>
                        </a>
                        <a href="{{ route('inventory.index') }}" class="flex items-center justify-center p-4 bg-white border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition">
                            <div class="text-center">
                                <div class="text-2xl mb-2">📦</div>
                                <p class="text-sm font-medium text-gray-700">View Items</p>
                            </div>
                        </a>
                        <a href="{{ route('inventory.audit.logs') }}" class="flex items-center justify-center p-4 bg-white border border-gray-200 rounded-lg hover:bg-yellow-50 hover:border-yellow-300 transition">
                            <div class="text-center">
                                <div class="text-2xl mb-2">📋</div>
                                <p class="text-sm font-medium text-gray-700">Audit Logs</p>
                            </div>
                        </a>
                    </div>
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
    </script>
</x-app-layout>