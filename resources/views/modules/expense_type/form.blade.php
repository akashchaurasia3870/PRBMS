<div class="space-y-6">
    <!-- Type Selection -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Expense Type
        </label>
        
        <!-- Predefined Types Dropdown -->
        <select id="type_select" onchange="handleTypeSelection()" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3">
            <option value="">Choose from common expense types...</option>
            @php
                $predefinedTypes = [
                    'Travel & Transportation',
                    'Food & Dining', 
                    'Office Supplies',
                    'Utilities',
                    'Marketing & Advertising',
                    'Training & Development',
                    'Equipment & Hardware',
                    'Maintenance & Repairs',
                    'Insurance',
                    'Software & Subscriptions',
                    'Rent & Facilities',
                    'Daily Basis Expenses',
                    'Work Related Expenses',
                    'Communication & Internet',
                    'Professional Services',
                    'Custom Type'
                ];
            @endphp
            @foreach($predefinedTypes as $type)
                <option value="{{ $type }}" {{ old('type', $expenseType->type ?? '') == $type ? 'selected' : '' }}>
                    {{ $type }}
                </option>
            @endforeach
        </select>
        
        <!-- Custom Type Input -->
        <input type="text" name="type" id="type_input" required 
               value="{{ old('type', $expenseType->type ?? '') }}"
               placeholder="Enter custom expense type or select from dropdown above..."
               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror">
        @error('type')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">Select from dropdown or type your own expense category</p>
    </div>

    <!-- Description -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Description
        </label>
        <textarea name="description" rows="3" required 
                  placeholder="Describe this expense type and when it should be used..."
                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $expenseType->description ?? '') }}</textarea>
        @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
        <a href="{{ route('expense_type.v1.index') }}" 
           class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-3 px-6 rounded-lg transition duration-200">
            ← Cancel
        </a>
        <button type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-8 rounded-lg transition duration-200 flex items-center">
            <span class="mr-2">💾</span>
            {{ isset($expenseType) ? 'Update Expense Type' : 'Save Expense Type' }}
        </button>
    </div>
</div>

<script>
let currentStep = 0;
const totalSteps = 2;
const steps = ['Ready to start', 'Expense Type', 'Description', 'Complete'];

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
    const typeInput = document.getElementById('type_input');
    const descriptionInput = document.querySelector('textarea[name="description"]');
    const submitBtn = document.querySelector('button[type="submit"]');
    
    updateProgress();
    
    if (typeInput) {
        typeInput.addEventListener('input', function() {
            if (this.value.trim()) {
                currentStep = Math.max(currentStep, 1);
                updateProgress();
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
    
    if (typeInput) typeInput.focus();
});
</script>

<script>
function handleTypeSelection() {
    const select = document.getElementById('type_select');
    const input = document.getElementById('type_input');
    
    if (select.value === 'Custom Type') {
        input.value = '';
        input.focus();
        input.placeholder = 'Enter your custom expense type...';
    } else if (select.value) {
        input.value = select.value;
        input.placeholder = 'You can modify the selected type if needed...';
    }
}

// Auto-select dropdown if there's existing data
document.addEventListener('DOMContentLoaded', function() {
    const typeInput = document.getElementById('type_input');
    const typeSelect = document.getElementById('type_select');
    
    if (typeInput.value) {
        const predefinedTypes = @json($predefinedTypes);
        if (predefinedTypes.includes(typeInput.value)) {
            typeSelect.value = typeInput.value;
        } else {
            typeSelect.value = 'Custom Type';
        }
    }
});
</script>