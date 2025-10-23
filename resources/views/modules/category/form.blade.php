<div class="space-y-6">
    <!-- Name Selection -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Category Name
        </label>
        
        <!-- Predefined Categories Dropdown -->
        <select id="name_select" onchange="handleNameSelection()" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3">
            <option value="">Choose from common categories...</option>
            @php
                $predefinedCategories = [
                    'Electronics',
                    'Furniture', 
                    'Office Supplies',
                    'Tools & Equipment',
                    'Clothing & Apparel',
                    'Books & Media',
                    'Sports & Recreation',
                    'Health & Beauty',
                    'Food & Beverages',
                    'Automotive',
                    'Home & Garden',
                    'Toys & Games',
                    'Art & Crafts',
                    'Industrial',
                    'Medical Supplies',
                    'Custom Category'
                ];
            @endphp
            @foreach($predefinedCategories as $cat)
                <option value="{{ $cat }}" {{ old('name', $category->name ?? '') == $cat ? 'selected' : '' }}>
                    {{ $cat }}
                </option>
            @endforeach
        </select>
        
        <!-- Custom Name Input -->
        <input type="text" name="name" id="name_input" required 
               value="{{ old('name', $category->name ?? '') }}"
               placeholder="Enter custom category name or select from dropdown above..."
               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
        @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">Select from dropdown or type your own category name</p>
    </div>

    <!-- Code -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Category Code
        </label>
        <input type="text" name="code" required 
               value="{{ old('code', $category->code ?? '') }}"
               placeholder="Enter unique category code (e.g., ELEC, FURN, OFF)"
               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror">
        @error('code')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">Short unique identifier for this category</p>
    </div>

    <!-- Description -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Description
        </label>
        <textarea name="description" rows="3" 
                  placeholder="Describe this category and what items it should contain..."
                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $category->description ?? '') }}</textarea>
        @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Color and Icon Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Category Color
            </label>
            <div class="flex items-center space-x-3">
                <input type="color" name="color" 
                       value="{{ old('color', $category->color ?? '#3B82F6') }}"
                       class="w-16 h-12 border border-gray-300 rounded-lg cursor-pointer">
                <input type="text" name="color_hex" 
                       value="{{ old('color', $category->color ?? '#3B82F6') }}"
                       placeholder="#3B82F6"
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <p class="text-gray-500 text-sm mt-1">Color for category identification</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Category Icon
            </label>
            <select name="icon" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @php
                    $icons = [
                        '📦' => '📦 Box',
                        '💻' => '💻 Electronics',
                        '🪑' => '🪑 Furniture',
                        '📝' => '📝 Office Supplies',
                        '🔧' => '🔧 Tools',
                        '👕' => '👕 Clothing',
                        '📚' => '📚 Books',
                        '⚽' => '⚽ Sports',
                        '💄' => '💄 Beauty',
                        '🍕' => '🍕 Food',
                        '🚗' => '🚗 Automotive',
                        '🏠' => '🏠 Home & Garden',
                        '🧸' => '🧸 Toys',
                        '🎨' => '🎨 Art & Crafts',
                        '🏭' => '🏭 Industrial',
                        '🏥' => '🏥 Medical'
                    ];
                @endphp
                @foreach($icons as $emoji => $label)
                    <option value="{{ $emoji }}" {{ old('icon', $category->icon ?? '📦') == $emoji ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <p class="text-gray-500 text-sm mt-1">Icon for visual identification</p>
        </div>
    </div>

    <!-- Status -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Status
        </label>
        <div class="flex items-center space-x-4">
            <label class="flex items-center">
                <input type="radio" name="is_active" value="1" 
                       {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                       class="mr-2 text-green-500 focus:ring-green-500">
                <span class="text-green-600">✅ Active</span>
            </label>
            <label class="flex items-center">
                <input type="radio" name="is_active" value="0" 
                       {{ !old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                       class="mr-2 text-red-500 focus:ring-red-500">
                <span class="text-red-600">❌ Inactive</span>
            </label>
        </div>
        <p class="text-gray-500 text-sm mt-1">Active categories can be used for new inventory items</p>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
        <a href="{{ route('category.v1.index') }}" 
           class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-3 px-6 rounded-lg transition duration-200">
            ← Cancel
        </a>
        <button type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-8 rounded-lg transition duration-200 flex items-center">
            <span class="mr-2">💾</span>
            {{ isset($category) ? 'Update Category' : 'Save Category' }}
        </button>
    </div>
</div>

<script>
let currentStep = 0;
const totalSteps = 2;
const steps = ['Ready to start', 'Category Name', 'Description', 'Complete'];

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

document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name_input');
    const codeInput = document.querySelector('input[name="code"]');
    const descriptionInput = document.querySelector('textarea[name="description"]');
    const submitBtn = document.querySelector('button[type="submit"]');
    
    updateProgress();
    
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            if (this.value.trim()) {
                currentStep = Math.max(currentStep, 1);
                updateProgress();
                // Auto-generate code from name
                if (codeInput && !codeInput.value) {
                    const code = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 4);
                    codeInput.value = code;
                }
                if (descriptionInput) descriptionInput.focus();
            }
        });
    }
    
    if (descriptionInput) {
        descriptionInput.addEventListener('input', function() {
            if (this.value.trim()) {
                currentStep = Math.max(currentStep, 2);
                updateProgress();
            }
        });
    }
    
    if (submitBtn) {
        submitBtn.addEventListener('click', function() {
            currentStep = 2;
            updateProgress();
        });
    }
    
    if (nameInput) nameInput.focus();
});
</script>

<script>
function handleNameSelection() {
    const select = document.getElementById('name_select');
    const input = document.getElementById('name_input');
    const codeInput = document.querySelector('input[name="code"]');
    
    if (select.value === 'Custom Category') {
        input.value = '';
        input.focus();
        input.placeholder = 'Enter your custom category name...';
    } else if (select.value) {
        input.value = select.value;
        input.placeholder = 'You can modify the selected category if needed...';
        // Auto-generate code
        if (codeInput && !codeInput.value) {
            const code = select.value.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 4);
            codeInput.value = code;
        }
    }
}

// Auto-select dropdown if there's existing data
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name_input');
    const nameSelect = document.getElementById('name_select');
    
    if (nameInput.value) {
        const predefinedCategories = @json($predefinedCategories);
        if (predefinedCategories.includes(nameInput.value)) {
            nameSelect.value = nameInput.value;
        } else {
            nameSelect.value = 'Custom Category';
        }
    }
});
</script>