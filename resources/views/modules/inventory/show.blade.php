<x-app-layout>
    <div class="py-2">
        <div class="mx-auto px-2">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="text-4xl text-white mr-4">📦</div>
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $data->item_name }}</h1>
                                <p class="text-indigo-100 mt-1">Inventory Item Details & Information</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-white text-sm opacity-75">Item Code</div>
                            <div class="text-white text-2xl font-bold">{{ $data->item_code }}</div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                        <!-- Item Details -->
                        <div class="lg:col-span-2 space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Item Information</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                        <div class="text-2xl mr-4">🏷️</div>
                                        <div>
                                            <div class="text-sm text-gray-500">Item Name</div>
                                            <div class="text-lg font-medium text-gray-900">{{ $data->item_name }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                        <div class="text-2xl mr-4">🔖</div>
                                        <div>
                                            <div class="text-sm text-gray-500">Item Code</div>
                                            <div class="text-lg font-medium text-gray-900">{{ $data->item_code }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                        <div class="text-2xl mr-4">📂</div>
                                        <div>
                                            <div class="text-sm text-gray-500">Category</div>
                                            <div class="text-lg font-medium text-gray-900">
                                                <a href="{{ route('category.show', $data->category->id) }}" class="text-blue-600 hover:text-blue-800">
                                                    {{ $data->category->name ?? 'N/A' }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @if($data->item_description)
                                        <div class="flex items-start p-4 bg-gray-50 rounded-lg">
                                            <div class="text-2xl mr-4 mt-1">📝</div>
                                            <div class="flex-1">
                                                <div class="text-sm text-gray-500">Description</div>
                                                <div class="text-gray-900">{{ $data->item_description }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Image and Quick Stats -->
                        <div class="space-y-6">
                            <!-- Item Image -->
                            @if($data->item_img_path)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🖼️ Item Image</h3>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <img src="{{ asset('storage/' . $data->item_img_path) }}" 
                                             alt="{{ $data->item_name }}" 
                                             class="w-full h-48 object-cover rounded-lg shadow-sm">
                                    </div>
                                </div>
                            @endif

                            <!-- Quick Actions -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">⚡ Quick Actions</h3>
                                <div class="space-y-2">
                                    <button onclick="openStockModal('in', {{ $data->id }}, '{{ $data->item_name }}')" 
                                            class="w-full bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg transition flex items-center justify-center">
                                        <span class="mr-2">➕</span> Stock In
                                    </button>
                                    <button onclick="openStockModal('out', {{ $data->id }}, '{{ $data->item_name }}', {{ $data->item_qty }})" 
                                            class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded-lg transition flex items-center justify-center">
                                        <span class="mr-2">➖</span> Stock Out
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                            <div class="flex items-center">
                                <div class="text-blue-600 text-3xl mr-4">💰</div>
                                <div>
                                    <p class="text-sm text-blue-600 font-medium">Unit Price</p>
                                    <p class="text-2xl font-bold text-blue-800">${{ number_format($data->item_price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                            <div class="flex items-center">
                                <div class="text-green-600 text-3xl mr-4">📦</div>
                                <div>
                                    <p class="text-sm text-green-600 font-medium">Current Stock</p>
                                    <p class="text-2xl font-bold text-green-800">{{ $data->item_qty }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                            <div class="flex items-center">
                                <div class="text-purple-600 text-3xl mr-4">💎</div>
                                <div>
                                    <p class="text-sm text-purple-600 font-medium">Total Value</p>
                                    <p class="text-2xl font-bold text-purple-800">${{ number_format($data->item_price * $data->item_qty, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                            <div class="flex items-center">
                                <div class="text-yellow-600 text-3xl mr-4">📊</div>
                                <div>
                                    <p class="text-sm text-yellow-600 font-medium">Status</p>
                                    <p class="text-lg font-bold {{ $data->item_qty > 10 ? 'text-green-800' : ($data->item_qty > 0 ? 'text-yellow-800' : 'text-red-800') }}">
                                        {{ $data->item_qty > 10 ? 'In Stock' : ($data->item_qty > 0 ? 'Low Stock' : 'Out of Stock') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="text-2xl mr-4">📅</div>
                                <div>
                                    <div class="text-sm text-gray-500">Created Date</div>
                                    <div class="text-lg font-medium text-gray-900">{{ $data->created_at->format('M d, Y \a\t H:i') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="text-2xl mr-4">🔄</div>
                                <div>
                                    <div class="text-sm text-gray-500">Last Updated</div>
                                    <div class="text-lg font-medium text-gray-900">{{ $data->updated_at->format('M d, Y \a\t H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('inventory.edit', $data->id) }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center">
                            <span class="mr-2">✏️</span>
                            Edit Item
                        </a>
                        <a href="{{ route('inventory.logs', $data->id) }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center">
                            <span class="mr-2">📋</span>
                            View Logs
                        </a>
                        <a href="{{ route('inventory.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center">
                            <span class="mr-2">←</span>
                            Back to Inventory
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