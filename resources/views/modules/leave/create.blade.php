<x-app-layout>
    <div class="">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">📝 Apply for Leave</h1>
                            <p class="text-gray-600 mt-1">Submit a new leave request</p>
                        </div>
                        <a href="{{ route('dashboard_leave.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                            ← Back to Leave Requests
                        </a>
                    </div>

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Form -->
                    <div class="max-w-2xl mx-auto">
                        <form action="{{ route('dashboard_leave.create_leave_request') }}" method="POST" class="space-y-6">
                            @csrf

                            <!-- User Selection -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        👤 Employee <span class="text-red-500">*</span>
                                    </label>
                                    <select name="user_id" id="user_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                        <option value="">Select Employee</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} (ID: {{ $user->id }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-2">
                                        📋 Leave Type <span class="text-red-500">*</span>
                                    </label>
                                    <select name="leave_type" id="leave_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                        <option value="">Select Leave Type</option>
                                        <option value="sick" {{ old('leave_type') == 'sick' ? 'selected' : '' }}>🤒 Sick Leave</option>
                                        <option value="casual" {{ old('leave_type') == 'casual' ? 'selected' : '' }}>🏖️ Casual Leave</option>
                                        <option value="annual" {{ old('leave_type') == 'annual' ? 'selected' : '' }}>🌴 Annual Leave</option>
                                        <option value="maternity" {{ old('leave_type') == 'maternity' ? 'selected' : '' }}>👶 Maternity Leave</option>
                                        <option value="paternity" {{ old('leave_type') == 'paternity' ? 'selected' : '' }}>👨‍👶 Paternity Leave</option>
                                        <option value="emergency" {{ old('leave_type') == 'emergency' ? 'selected' : '' }}>🚨 Emergency Leave</option>
                                        <option value="unpaid" {{ old('leave_type') == 'unpaid' ? 'selected' : '' }}>💸 Unpaid Leave</option>
                                    </select>
                                    @error('leave_type')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Date Range -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        📅 Start Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" 
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           min="{{ date('Y-m-d') }}" required>
                                    @error('start_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        📅 End Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" 
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           min="{{ date('Y-m-d') }}" required>
                                    @error('end_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Duration Display -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="text-blue-600 mr-2">⏱️</div>
                                    <div>
                                        <div class="text-sm font-medium text-blue-800">Leave Duration</div>
                                        <div id="duration" class="text-sm text-blue-600">Select dates to calculate duration</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reason -->
                            <div>
                                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                                    📝 Reason <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="reason" id="reason" value="{{ old('reason') }}" 
                                       maxlength="255" placeholder="Brief reason for leave request"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                @error('reason')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    📄 Description
                                </label>
                                <textarea name="description" id="description" rows="4" 
                                          placeholder="Additional details about your leave request (optional)"
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex flex-col sm:flex-row gap-3 pt-6">
                                <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center justify-center">
                                    📤 Submit Leave Request
                                </button>
                                <a href="{{ route('dashboard_leave.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg font-medium transition duration-200 text-center">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const durationDiv = document.getElementById('duration');

        function calculateDuration() {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                
                if (end >= start) {
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    
                    durationDiv.innerHTML = `<strong>${diffDays}</strong> day${diffDays !== 1 ? 's' : ''} 
                                           <span class="text-xs">(${start.toLocaleDateString()} to ${end.toLocaleDateString()})</span>`;
                } else {
                    durationDiv.innerHTML = '<span class="text-red-600">End date must be after start date</span>';
                }
            } else {
                durationDiv.innerHTML = 'Select dates to calculate duration';
            }
        }

        // Update end date minimum when start date changes
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
            calculateDuration();
        });

        endDateInput.addEventListener('change', calculateDuration);
    });
    </script>
</x-app-layout>