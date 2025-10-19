
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-1">Item Code</label>
    <input type="text" name="item_code" class="w-full border rounded px-3 py-2" value="{{ old('item_code', $inventory->item_code ?? '') }}" required>
    @error('item_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
</div>
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-1">Item Name</label>
    <input type="text" name="item_name" class="w-full border rounded px-3 py-2" value="{{ old('item_name', $inventory->item_name ?? '') }}" required>
    @error('item_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
</div>
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-1">Category</label>
    <select name="category_id" class="w-full border rounded px-3 py-2" required>
        <option value="">Select Category</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id', $inventory->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
</div>
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-1">Description</label>
    <textarea name="item_description" class="w-full border rounded px-3 py-2">{{ old('item_description', $inventory->item_description ?? '') }}</textarea>
    @error('item_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
</div>
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-1">Quantity</label>
    <input type="number" name="item_qty" min="0" class="w-full border rounded px-3 py-2" value="{{ old('item_qty', $inventory->item_qty ?? 0) }}" required>
    @error('item_qty') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
</div>
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-1">Price</label>
    <input type="number" name="item_price" class="w-full border rounded px-3 py-2" value="{{ old('item_price', $inventory->item_price ?? 0) }}" required>
    @error('item_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
</div>
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-1">Image</label>
    <input type="file" name="item_img_path" class="w-full border rounded px-3 py-2">
    @if(isset($inventory) && $inventory->item_img_path)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $inventory->item_img_path) }}" width="100" alt="Inventory Image" class="rounded shadow">
        </div>
    @endif
    @error('item_img_path') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
</div>
