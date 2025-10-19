<div class="space-y-6">
    <!-- Type Selection -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Expense Type
        </label>
        <select name="type" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type') border-red-500 @enderror">
            <option value="">Select expense type...</option>
            @if(isset($expenseTypes) && is_iterable($expenseTypes))
                @foreach ($expenseTypes as $expenseType)
                    <option value="{{ $expenseType->type }}" {{ old('type', $expense->type ?? '') == $expenseType->type ? 'selected' : '' }}>
                        {{ $expenseType->expense_id }} - {{ $expenseType->type }}
                    </option>
                @endforeach
            @elseif(isset($data) && is_iterable($data))
                @foreach ($data as $expenseType)
                    <option value="{{ $expenseType->type }}" {{ old('type', $expense->type ?? '') == $expenseType->type ? 'selected' : '' }}>
                        {{ $expenseType->expense_id }} - {{ $expenseType->type }}
                    </option>
                @endforeach
            @endif
        </select>
        @error('type')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">Choose from predefined expense categories</p>
    </div>

    <!-- Description -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Description
        </label>
        <textarea name="description" rows="3" required placeholder="Enter detailed description of the expense..." 
                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $expense->description ?? '') }}</textarea>
        @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">Provide clear details about this expense</p>
    </div>

    <!-- Amount -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Amount
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">$</span>
            </div>
            <input type="number" step="0.01" min="0" name="amount" required 
                   value="{{ old('amount', $expense->amount ?? '') }}"
                   placeholder="0.00"
                   class="w-full pl-8 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('amount') border-red-500 @enderror">
        </div>
        @error('amount')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">Enter the expense amount in USD</p>
    </div>

    <!-- Expense Date -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Expense Date
        </label>
        <input type="date" name="expense_date" required 
               value="{{ old('expense_date', isset($expense) ? $expense->expense_date : date('Y-m-d')) }}"
               max="{{ date('Y-m-d') }}"
               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('expense_date') border-red-500 @enderror">
        @error('expense_date')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">When did this expense occur?</p>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
        <a href="{{ route('expense.v1.index') }}" 
           class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-3 px-6 rounded-lg transition duration-200">
            ← Cancel
        </a>
        <button type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-8 rounded-lg transition duration-200 flex items-center">
            <span class="mr-2">💾</span>
            {{ isset($expense) ? 'Update Expense' : 'Save Expense' }}
        </button>
    </div>
</div>

<script>
let currentStep = 0;
const totalSteps = 5;
const steps = ['Ready to start', 'Expense Type', 'Description', 'Amount', 'Date', 'Complete'];

function updateProgress() {
    const progress = (currentStep / totalSteps) * 100;
    document.getElementById('progressBar').style.width = progress + '%';
    document.getElementById('progressText').textContent = `Step ${currentStep} of ${totalSteps}: ${steps[currentStep]}`;
}

document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.querySelector('select[name="type"]');
    const descriptionInput = document.querySelector('textarea[name="description"]');
    const amountInput = document.querySelector('input[name="amount"]');
    const dateInput = document.querySelector('input[name="expense_date"]');
    const submitBtn = document.querySelector('button[type="submit"]');
    
    updateProgress();
    
    typeSelect.addEventListener('change', function() {
        if (this.value) {
            currentStep = Math.max(currentStep, 1);
            updateProgress();
            descriptionInput.focus();
        }
    });
    
    descriptionInput.addEventListener('input', function() {
        if (this.value.trim()) {
            currentStep = Math.max(currentStep, 2);
            updateProgress();
        }
    });
    
    amountInput.addEventListener('input', function() {
        if (this.value) {
            currentStep = Math.max(currentStep, 3);
            updateProgress();
        }
    });
    
    dateInput.addEventListener('change', function() {
        if (this.value) {
            currentStep = Math.max(currentStep, 4);
            updateProgress();
        }
    });
    
    submitBtn.addEventListener('click', function() {
        currentStep = 5;
        updateProgress();
    });
    
    typeSelect.focus();
});
</script>