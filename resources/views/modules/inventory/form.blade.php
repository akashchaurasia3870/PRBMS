<div class="space-y-6">
    <!-- Category Selection -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Category
        </label>
        <select name="category_id" id="category_select" onchange="handleCategorySelection()" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 @error('category_id') border-red-500 @enderror" required>
            <option value="">Select a category...</option>
            @php
                $categoryList = $categories ?? ($data ?? []);
            @endphp
            @foreach($categoryList as $cat)
                <option value="{{ $cat->id }}" data-code="{{ $cat->code }}" data-name="{{ $cat->name }}" {{ old('category_id', $inventory->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }} ({{ $cat->code }})</option>
            @endforeach
        </select>
        @error('category_id')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">Choose the category for this inventory item</p>
    </div>

    <!-- Item Code -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Item Code
        </label>
        <input type="text" name="item_code" id="item_code" required 
               value="{{ old('item_code', $inventory->item_code ?? '') }}"
               placeholder="Auto-generated from category and name..."
               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 @error('item_code') border-red-500 @enderror">
        @error('item_code')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">Unique identifier for this item (auto-generated or custom)</p>
    </div>

    <!-- Item Name -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Item Name
        </label>
        <input type="text" name="item_name" id="item_name" required 
               value="{{ old('item_name', $inventory->item_name ?? '') }}"
               placeholder="Enter descriptive name for the item..."
               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 @error('item_name') border-red-500 @enderror">
        @error('item_name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">Clear, descriptive name for the inventory item</p>
    </div>
    <!-- Description -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Description
        </label>
        <textarea name="item_description" rows="3" 
                  placeholder="Detailed description of the item, specifications, notes..."
                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 @error('item_description') border-red-500 @enderror">{{ old('item_description', $inventory->item_description ?? '') }}</textarea>
        @error('item_description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Price and Quantity Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="text-red-500">*</span> Unit Price
            </label>
            <div class="relative">
                <span class="absolute left-3 top-3 text-gray-500">$</span>
                <input type="number" name="item_price" step="0.01" min="0" required 
                       value="{{ old('item_price', $inventory->item_price ?? '') }}"
                       placeholder="0.00"
                       class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 @error('item_price') border-red-500 @enderror">
            </div>
            @error('item_price')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="text-red-500">*</span> Initial Quantity
            </label>
            <input type="number" name="item_qty" min="0" required 
                   value="{{ old('item_qty', $inventory->item_qty ?? 0) }}"
                   placeholder="0"
                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 @error('item_qty') border-red-500 @enderror">
            @error('item_qty')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Stock Levels Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Minimum Stock Level
            </label>
            <input type="number" name="min_stock_level" min="0" 
                   value="{{ old('min_stock_level', $inventory->min_stock_level ?? 5) }}"
                   placeholder="5"
                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
            <p class="text-gray-500 text-sm mt-1">Alert when stock falls below this level</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Maximum Stock Level
            </label>
            <input type="number" name="max_stock_level" min="0" 
                   value="{{ old('max_stock_level', $inventory->max_stock_level ?? 100) }}"
                   placeholder="100"
                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
            <p class="text-gray-500 text-sm mt-1">Maximum recommended stock level</p>
        </div>
    </div>

    <!-- Additional Details Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Location
            </label>
            <input type="text" name="location" 
                   value="{{ old('location', $inventory->location ?? '') }}"
                   placeholder="e.g., Warehouse A-1, Room 205"
                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
            <p class="text-gray-500 text-sm mt-1">Physical location of the item</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Supplier
            </label>
            <input type="text" name="supplier" 
                   value="{{ old('supplier', $inventory->supplier ?? '') }}"
                   placeholder="e.g., ABC Company, XYZ Corp"
                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
            <p class="text-gray-500 text-sm mt-1">Item supplier or vendor</p>
        </div>
    </div>

    <!-- Barcode and Expiry Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Barcode
            </label>
            <x-barcode-scanner name="barcode" value="{{ old('barcode', $inventory->barcode ?? '') }}" />
            <p class="text-gray-500 text-sm mt-1">Barcode for scanning (auto-generated if empty)</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Expiry Date
            </label>
            <input type="date" name="expiry_date" 
                   value="{{ old('expiry_date', $inventory->expiry_date ?? '') }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
            <p class="text-gray-500 text-sm mt-1">Expiry date (if applicable)</p>
        </div>
    </div>

    <!-- Image Upload -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Item Image
        </label>
        <input type="file" name="item_img_path" accept="image/*" 
               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 @error('item_img_path') border-red-500 @enderror">
        @if(isset($inventory) && $inventory->item_img_path)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $inventory->item_img_path) }}" width="100" alt="Current Image" class="rounded shadow">
            </div>
        @endif
        @error('item_img_path')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">Upload an image for this item (optional)</p>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
        <a href="{{ route('inventory.index') }}" 
           class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-3 px-6 rounded-lg transition duration-200">
            ← Cancel
        </a>
        <button type="submit" 
                class="bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-8 rounded-lg transition duration-200 flex items-center">
            <span class="mr-2">💾</span>
            {{ isset($inventory) ? 'Update Item' : 'Save Item' }}
        </button>
    </div>
</div>

<script>
let currentStep = 0;
const totalSteps = 3;
const steps = ['Ready to start', 'Category & Code', 'Item Details', 'Complete'];

function updateProgress() {
    const progress = (currentStep / totalSteps) * 100;
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    if (progressBar) {
        progressBar.style.width = progress + '%';
    }
    if (progressText) {
        progressText.textContent = `Step ${currentStep} of ${totalSteps}: ${steps[currentStep]}`;
    }
}

function handleCategorySelection() {
    const select = document.getElementById('category_select');
    const itemCode = document.getElementById('item_code');
    const itemName = document.getElementById('item_name');
    
    if (select.value) {
        const option = select.options[select.selectedIndex];
        const categoryCode = option.getAttribute('data-code');
        const categoryName = option.getAttribute('data-name');
        
        // Auto-populate item code if empty
        if (!itemCode.value) {
            const timestamp = Date.now().toString().slice(-4);
            itemCode.value = categoryCode + '-' + timestamp;
        }
        
        // Focus on item name
        itemName.focus();
        
        currentStep = Math.max(currentStep, 1);
        updateProgress();
    }
}

function generateItemCode() {
    const categorySelect = document.getElementById('category_select');
    const itemName = document.getElementById('item_name');
    const itemCode = document.getElementById('item_code');
    
    if (categorySelect.value && itemName.value) {
        const option = categorySelect.options[categorySelect.selectedIndex];
        const categoryCode = option.getAttribute('data-code');
        const nameCode = itemName.value.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 3);
        const timestamp = Date.now().toString().slice(-3);
        
        itemCode.value = categoryCode + '-' + nameCode + '-' + timestamp;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_select');
    const itemName = document.getElementById('item_name');
    const itemPrice = document.querySelector('input[name="item_price"]');
    const submitBtn = document.querySelector('button[type="submit"]');
    
    updateProgress();
    
    if (itemName) {
        itemName.addEventListener('input', function() {
            if (this.value.trim()) {
                generateItemCode();
                currentStep = Math.max(currentStep, 2);
                updateProgress();
            }
        });
    }
    
    if (itemPrice) {
        itemPrice.addEventListener('input', function() {
            if (this.value) {
                currentStep = Math.max(currentStep, 3);
                updateProgress();
            }
        });
    }
    
    if (submitBtn) {
        submitBtn.addEventListener('click', function() {
            currentStep = 3;
            updateProgress();
        });
    }
    
    if (categorySelect) categorySelect.focus();
});

function generateBarcode() {
    const barcodeInput = document.getElementById('barcode_input');
    const timestamp = Date.now().toString();
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    const barcode = 'BC' + timestamp.slice(-8) + random;
    barcodeInput.value = barcode;
}
</script>
