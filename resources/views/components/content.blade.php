<div>
    <h1 class="text-3xl font-bold mb-6 text-gray-800">📊 Dashboard Overview</h1>
    
    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @php
            $totalItems = \App\Models\Inventory::count();
            $totalValue = \App\Models\Inventory::sum(\DB::raw('item_price * item_qty'));
            $lowStockItems = \App\Models\Inventory::lowStock()->count();
            $totalCategories = \App\Models\Category::active()->count();
        @endphp
        
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Items</p>
                    <p class="text-3xl font-bold">{{ number_format($totalItems) }}</p>
                </div>
                <div class="text-4xl opacity-80">📦</div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Value</p>
                    <p class="text-3xl font-bold">${{ number_format($totalValue, 2) }}</p>
                </div>
                <div class="text-4xl opacity-80">💰</div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-red-500 to-red-600 text-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Low Stock Items</p>
                    <p class="text-3xl font-bold">{{ $lowStockItems }}</p>
                </div>
                <div class="text-4xl opacity-80">⚠️</div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Categories</p>
                    <p class="text-3xl font-bold">{{ $totalCategories }}</p>
                </div>
                <div class="text-4xl opacity-80">🏷️</div>
            </div>
        </div>
    </div>
    
    <!-- Charts and Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Category Distribution -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">📊 Inventory by Category</h3>
            @php
                $categoryStats = \App\Models\Category::withCount('inventories')
                    ->having('inventories_count', '>', 0)
                    ->get();
            @endphp
            
            <div class="space-y-3">
                @foreach($categoryStats as $category)
                    @php
                        $percentage = $totalItems > 0 ? ($category->inventories_count / $totalItems) * 100 : 0;
                    @endphp
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-2xl">{{ $category->icon ?? '📦' }}</span>
                            <span class="font-medium">{{ $category->name }}</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-sm font-medium w-12 text-right">{{ $category->inventories_count }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">🕒 Recent Inventory Updates</h3>
            @php
                $recentItems = \App\Models\Inventory::with('category')
                    ->orderBy('updated_at', 'desc')
                    ->limit(8)
                    ->get();
            @endphp
            
            <div class="space-y-3">
                @foreach($recentItems as $item)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <div>
                                <p class="font-medium text-sm">{{ Str::limit($item->item_name, 25) }}</p>
                                <p class="text-xs text-gray-500">{{ $item->category->name ?? 'No Category' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium">{{ $item->item_qty }} units</p>
                            <p class="text-xs text-gray-500">{{ $item->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Low Stock Alerts -->
    @if($lowStockItems > 0)
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
        <h3 class="text-xl font-semibold mb-4 text-red-800 flex items-center">
            <span class="mr-2">⚠️</span> Low Stock Alerts
        </h3>
        @php
            $lowStockList = \App\Models\Inventory::lowStock()->with('category')->limit(10)->get();
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($lowStockList as $item)
                <div class="bg-white p-4 rounded-lg border border-red-200">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-sm">{{ Str::limit($item->item_name, 20) }}</h4>
                        <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">{{ $item->category->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Current: {{ $item->item_qty }}</span>
                        <span class="text-sm text-red-600">Min: {{ $item->min_stock_level ?? 'Not set' }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- Quick Actions -->
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-xl font-semibold mb-4 text-gray-800">🚀 Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('inventory.create') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg text-center transition duration-200">
                <div class="text-2xl mb-2">➕</div>
                <div class="text-sm font-medium">Add Item</div>
            </a>
            <a href="{{ route('category.v1.new') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg text-center transition duration-200">
                <div class="text-2xl mb-2">🏷️</div>
                <div class="text-sm font-medium">Add Category</div>
            </a>
            <a href="{{ route('inventory.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg text-center transition duration-200">
                <div class="text-2xl mb-2">📋</div>
                <div class="text-sm font-medium">View Inventory</div>
            </a>
            <a href="{{ route('category.v1.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white p-4 rounded-lg text-center transition duration-200">
                <div class="text-2xl mb-2">📂</div>
                <div class="text-sm font-medium">Manage Categories</div>
            </a>
        </div>
    </div>
</div>