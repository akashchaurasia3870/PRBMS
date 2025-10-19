<div class="mb-4">
    <label for="name" class="block text-gray-700 font-medium mb-1">Name</label>
    <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2" value="{{ old('name', $category->name ?? '') }}" required>
    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
</div>

<div class="mb-4">
    <label for="code" class="block text-gray-700 font-medium mb-1">Code</label>
    <input type="text" name="code" id="code" class="w-full border rounded px-3 py-2" value="{{ old('code', $category->code ?? '') }}" required>
    @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
</div>

<div class="mb-4">
    <label for="description" class="block text-gray-700 font-medium mb-1">Description</label>
    <textarea name="description" id="description" class="w-full border rounded px-3 py-2">{{ old('description', $category->description ?? '') }}</textarea>
    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
</div>
