<div class="space-y-6">
    <!-- Name -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Full Name
        </label>
        <input type="text" name="name" required 
               value="{{ old('name', $user->name ?? '') }}"
               placeholder="Enter user's full name"
               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
        @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">Enter the user's complete name</p>
    </div>

    <!-- Email -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Email Address
        </label>
        <input type="email" name="email" required 
               value="{{ old('email', $user->email ?? '') }}"
               placeholder="Enter email address"
               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
        @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">This will be used for login and notifications</p>
    </div>

    <!-- Password -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Password
        </label>
        <input type="password" name="password" {{ isset($user) ? '' : 'required' }}
               placeholder="{{ isset($user) ? 'Leave blank to keep current password' : 'Enter password' }}"
               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror">
        @error('password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">{{ isset($user) ? 'Leave blank to keep current password' : 'Minimum 8 characters required' }}</p>
    </div>

    <!-- Confirm Password -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            <span class="text-red-500">*</span> Confirm Password
        </label>
        <input type="password" name="password_confirmation" {{ isset($user) ? '' : 'required' }}
               placeholder="{{ isset($user) ? 'Confirm new password if changing' : 'Confirm password' }}"
               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password_confirmation') border-red-500 @enderror">
        @error('password_confirmation')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">Re-enter the password to confirm</p>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
        <a href="{{ route('dashboard_list.user') }}" 
           class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-3 px-6 rounded-lg transition duration-200">
            ← Cancel
        </a>
        <button type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-8 rounded-lg transition duration-200 flex items-center">
            <span class="mr-2">💾</span>
            {{ isset($user) ? 'Update User' : 'Create User' }}
        </button>
    </div>
</div>

<script>
let currentStep = 0;
const totalSteps = 4;
const steps = ['Ready to start', 'Name', 'Email', 'Password', 'Complete'];

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
    const nameInput = document.querySelector('input[name="name"]');
    const emailInput = document.querySelector('input[name="email"]');
    const passwordInput = document.querySelector('input[name="password"]');
    const submitBtn = document.querySelector('button[type="submit"]');
    
    updateProgress();
    
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            if (this.value.trim()) {
                currentStep = Math.max(currentStep, 1);
                updateProgress();
                if (emailInput) emailInput.focus();
            }
        });
    }
    
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            if (this.value.trim()) {
                currentStep = Math.max(currentStep, 2);
                updateProgress();
                if (passwordInput) passwordInput.focus();
            }
        });
    }
    
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            if (this.value.trim()) {
                currentStep = Math.max(currentStep, 3);
                updateProgress();
            }
        });
    }
    
    if (submitBtn) {
        submitBtn.addEventListener('click', function() {
            currentStep = 4;
            updateProgress();
        });
    }
    
    if (nameInput) nameInput.focus();
});
</script>