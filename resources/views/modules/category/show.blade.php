<x-app-layout>
    <div class="py-2">
        <div class="mx-auto px-2">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="text-4xl text-white mr-4">🏷️</div>
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $data->name }}</h1>
                                <p class="text-purple-100 mt-1">Category Details & Information</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-white text-sm opacity-75">Category ID</div>
                            <div class="text-white text-2xl font-bold">#{{ $data->id }}</div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Basic Information</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                        <div class="text-2xl mr-4">🏷️</div>
                                        <div>
                                            <div class="text-sm text-gray-500">Category Name</div>
                                            <div class="text-lg font-medium text-gray-900">{{ $data->name }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                        <div class="text-2xl mr-4">🔖</div>
                                        <div>
                                            <div class="text-sm text-gray-500">Category Code</div>
                                            <div class="text-lg font-medium text-gray-900">{{ $data->code }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-start p-4 bg-gray-50 rounded-lg">
                                        <div class="text-2xl mr-4 mt-1">📝</div>
                                        <div class="flex-1">
                                            <div class="text-sm text-gray-500">Description</div>
                                            <div class="text-gray-900">{{ $data->description ?: 'No description provided' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Statistics</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                                        <div class="text-2xl mr-4">📦</div>
                                        <div>
                                            <div class="text-sm text-blue-600">Total Items</div>
                                            <div class="text-2xl font-bold text-blue-800">{{ $data->inventories->count() }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center p-4 bg-green-50 rounded-lg border border-green-200">
                                        <div class="text-2xl mr-4">📅</div>
                                        <div>
                                            <div class="text-sm text-green-600">Created Date</div>
                                            <div class="text-lg font-medium text-green-800">{{ $data->created_at->format('M d, Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                                        <div class="text-2xl mr-4">🔄</div>
                                        <div>
                                            <div class="text-sm text-yellow-600">Last Updated</div>
                                            <div class="text-lg font-medium text-yellow-800">{{ $data->updated_at->format('M d, Y') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Items -->
                    @if($data->inventories->count() > 0)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">📦 Inventory Items ({{ $data->inventories->count() }})</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($data->inventories->take(6) as $item)
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->item_name }}</div>
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $item->item_code }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm text-gray-500">
                                            <span>Qty: {{ $item->item_qty }}</span>
                                            <span>${{ number_format($item->item_price, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($data->inventories->count() > 6)
                                <div class="mt-4 text-center">
                                    <a href="{{ route('inventory.index', ['category' => $data->name]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View all {{ $data->inventories->count() }} items →
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="mb-8 text-center py-8 bg-gray-50 rounded-lg">
                            <div class="text-4xl mb-2">📦</div>
                            <div class="text-gray-500">No inventory items in this category yet</div>
                            <a href="{{ route('inventory.v1.new') }}" class="inline-block mt-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                                Add First Item
                            </a>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('category.edit', $data->id) }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center">
                            <span class="mr-2">✏️</span>
                            Edit Category
                        </a>
                        <a href="{{ route('inventory.v1.new') }}?category={{ $data->id }}" 
                           class="bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center">
                            <span class="mr-2">➕</span>
                            Add Item
                        </a>
                        <a href="{{ route('category.audit.logs') }}?category={{ $data->id }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center">
                            <span class="mr-2">📋</span>
                            View Logs
                        </a>
                        <a href="{{ route('category.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center">
                            <span class="mr-2">←</span>
                            Back to Categories
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>