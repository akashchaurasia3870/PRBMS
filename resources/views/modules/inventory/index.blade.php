<x-app-layout>
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Inventory List</h2>
        <a href="{{ route('inventory.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 transition">Add Inventory Item</a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            {{ session('success') }}
            <button type="button" class="absolute top-2 right-2 text-green-700" onclick="this.parentElement.remove()">&times;</button>
        </div>
    @endif

    <div class="bg-white shadow rounded">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Item Code</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Name</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Category</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Quantity</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Price</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Image</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 w-72">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                @forelse($inventories as $item)
                    <tr>
                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $item->item_code }}</td>
                        <td class="px-4 py-3">{{ $item->item_name }}</td>
                        <td class="px-4 py-3">{{ $item->category->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2 py-1 rounded text-xs font-medium {{ $item->item_qty > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item->item_qty }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2 py-1 rounded text-xs font-medium {{ $item->item_price > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item->item_price }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($item->item_img_path)
                                <img src="{{ asset('storage/' . $item->item_img_path) }}" width="50" class="rounded shadow">
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 space-x-1">
                            <button class="inline-flex items-center px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-green-700 transition" data-modal-target="stockInModal{{ $item->id }}">
                                <i class="bi bi-plus-circle mr-1"></i> Stock In
                            </button>
                            <button class="inline-flex items-center px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-yellow-600 transition" data-modal-target="stockOutModal{{ $item->id }}">
                                <i class="bi bi-dash-circle mr-1"></i> Stock Out
                            </button>
                            <a href="{{ route('inventory.logs', $item->id) }}" class="inline-flex items-center px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition">
                                <i class="bi bi-clock-history mr-1"></i> Logs
                            </a>
                            <a href="{{ route('inventory.show', $item->id) }}" class="inline-flex items-center px-2 py-1 border border-blue-400 text-blue-600 text-xs rounded hover:bg-blue-50 transition">
                                <i class="bi bi-eye mr-1"></i>
                            </a>
                            <a href="{{ route('inventory.edit', $item->id) }}" class="inline-flex items-center px-2 py-1 border border-yellow-400 text-yellow-600 text-xs rounded hover:bg-yellow-50 transition">
                                <i class="bi bi-pencil mr-1"></i>
                            </a>
                            <form method="POST" action="{{ route('inventory.destroy', $item->id) }}" class="inline-block">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Delete this item?')" class="inline-flex items-center px-2 py-1 border border-red-400 text-red-600 text-xs rounded hover:bg-red-50 transition">
                                    <i class="bi bi-trash mr-1"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Stock In Modal -->
                    <div id="stockInModal{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center">
                        <div class="bg-white rounded shadow-lg w-full max-w-md mx-auto">
                            <form method="POST" action="{{ route('inventory.stockIn', $item->id) }}">
                                @csrf
                                <div class="flex justify-between items-center px-6 py-4 border-b">
                                    <h5 class="text-lg font-semibold flex items-center">
                                        <i class="bi bi-plus-circle text-green-500 mr-2"></i> Stock In: {{ $item->item_name }}
                                    </h5>
                                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal('stockInModal{{ $item->id }}')">&times;</button>
                                </div>
                                <div class="px-6 py-4">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-1">Quantity to Add</label>
                                        <input type="number" min="1" name="quantity" class="w-full border rounded px-3 py-2" required placeholder="Enter quantity">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-1">Description <span class="text-gray-400">(optional)</span></label>
                                        <textarea name="description" class="w-full border rounded px-3 py-2" rows="2" placeholder="Reason or note"></textarea>
                                    </div>
                                </div>
                                <div class="flex justify-end space-x-2 px-6 py-4 border-t">
                                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300" onclick="closeModal('stockInModal{{ $item->id }}')">Cancel</button>
                                    <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700" type="submit">Add Stock</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Stock Out Modal -->
                    <div id="stockOutModal{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center">
                        <div class="bg-white rounded shadow-lg w-full max-w-md mx-auto">
                            <form method="POST" action="{{ route('inventory.stockOut', $item->id) }}">
                                @csrf
                                <div class="flex justify-between items-center px-6 py-4 border-b">
                                    <h5 class="text-lg font-semibold flex items-center">
                                        <i class="bi bi-dash-circle text-red-500 mr-2"></i> Stock Out: {{ $item->item_name }}
                                    </h5>
                                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal('stockOutModal{{ $item->id }}')">&times;</button>
                                </div>
                                <div class="px-6 py-4">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-1">Quantity to Remove</label>
                                        <input type="number" min="1" max="{{ $item->item_qty }}" name="quantity" class="w-full border rounded px-3 py-2" required placeholder="Enter quantity">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-1">Description <span class="text-gray-400">(optional)</span></label>
                                        <textarea name="description" class="w-full border rounded px-3 py-2" rows="2" placeholder="Reason or note"></textarea>
                                    </div>
                                </div>
                                <div class="flex justify-end space-x-2 px-6 py-4 border-t">
                                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300" onclick="closeModal('stockOutModal{{ $item->id }}')">Cancel</button>
                                    <button class="px-4 py-2 bg-red-500 text-white rounded hover:bg-yellow-600" type="submit">Remove Stock</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-400 py-8">No data found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">
        {{ $inventories->links() }}
    </div>
</div>

<!-- Bootstrap Icons CDN (if not already included) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<!-- Modal JS for Tailwind (simple) -->
<script>
    document.querySelectorAll('[data-modal-target]').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById(this.getAttribute('data-modal-target')).classList.remove('hidden');
        });
    });
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
</x-app-layout>
