<div class="space-y-6">
    <!-- Role Name -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Role Name
        </label>
        <input type="text" name="role_name" required 
               value="{{ old('role_name', $role->role_name ?? '') }}"
               placeholder="Enter role name"
               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role_name') border-red-500 @enderror">
        @error('role_name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">Enter a unique name for this role</p>
    </div>

    <!-- Role Description -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Description
        </label>
        <textarea name="role_desc" rows="3" required 
                  placeholder="Describe the role and its responsibilities..."
                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role_desc') border-red-500 @enderror">{{ old('role_desc', $role->role_desc ?? '') }}</textarea>
        @error('role_desc')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">Provide a clear description of this role's purpose</p>
    </div>

    <!-- Role Level -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Role Level
        </label>
        <select name="role_lvl" required 
                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role_lvl') border-red-500 @enderror">
            <option value="">Select role level...</option>
            <option value="0" {{ old('role_lvl', $role->role_lvl ?? '') == '0' ? 'selected' : '' }}>Level 0 - Basic User</option>
            <option value="1" {{ old('role_lvl', $role->role_lvl ?? '') == '1' ? 'selected' : '' }}>Level 1 - Standard User</option>
            <option value="2" {{ old('role_lvl', $role->role_lvl ?? '') == '2' ? 'selected' : '' }}>Level 2 - Supervisor</option>
            <option value="3" {{ old('role_lvl', $role->role_lvl ?? '') == '3' ? 'selected' : '' }}>Level 3 - Administrator</option>
        </select>
        @error('role_lvl')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">Higher levels have more permissions and access</p>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
        <a href="{{ route('dashboard_list.roles') }}" 
           class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-3 px-6 rounded-lg transition duration-200">
            ← Cancel
        </a>
        <button type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-8 rounded-lg transition duration-200 flex items-center">
            <span class="mr-2">💾</span>
            {{ isset($role) ? 'Update Role' : 'Create Role' }}
        </button>
    </div>
</div>

<script>
let currentStep = 0;
const totalSteps = 3;
const steps = ['Ready to start', 'Role Name', 'Description', 'Level', 'Complete'];

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
    const nameInput = document.querySelector('input[name="role_name"]');
    const descInput = document.querySelector('textarea[name="role_desc"]');
    const levelSelect = document.querySelector('select[name="role_lvl"]');
    const submitBtn = document.querySelector('button[type="submit"]');
    
    updateProgress();
    
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            if (this.value.trim()) {
                currentStep = Math.max(currentStep, 1);
                updateProgress();
                if (descInput) descInput.focus();
            }
        });
    }
    
    if (descInput) {
        descInput.addEventListener('input', function() {
            if (this.value.trim()) {
                currentStep = Math.max(currentStep, 2);
                updateProgress();
                if (levelSelect) levelSelect.focus();
            }
        });
    }
    
    if (levelSelect) {
        levelSelect.addEventListener('change', function() {
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
    
    if (nameInput) nameInput.focus();
});
</script>