<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">📝 Apply for Leave</h1>
                        <p class="text-gray-600 mt-1">Submit your leave request for approval</p>
                    </div>
                    <a href="{{ route('dashboard_leave.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                        ← Back to Leave List
                    </a>
                </div>

                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        ❌ {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Leave Application Form -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200">
                    <form action="{{ route('dashboard_leave.create_leave_request') }}" method="POST" id="leaveForm" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- User Selection -->
                            <div class="md:col-span-2">
                                <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">👤 Employee</label>
                                <select name="user_id" id="user_id" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    @if(count($users) === 1)
                                        <option value="{{ $users[0]->id }}" selected>{{ $users[0]->name }}</option>
                                    @else
                                        <option value="" disabled selected>Select employee</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- Leave Type -->
                            <div>
                                <label for="leave_type" class="block text-sm font-semibold text-gray-700 mb-2">🏷️ Leave Type</label>
                                <select name="leave_type" id="leave_type" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    <option value="" disabled selected>Select leave type</option>
                                    <option value="sick" {{ old('leave_type') == 'sick' ? 'selected' : '' }}>🤒 Sick Leave</option>
                                    <option value="vacation" {{ old('leave_type') == 'vacation' ? 'selected' : '' }}>🏖️ Vacation</option>
                                    <option value="personal" {{ old('leave_type') == 'personal' ? 'selected' : '' }}>👤 Personal Leave</option>
                                    <option value="emergency" {{ old('leave_type') == 'emergency' ? 'selected' : '' }}>🚨 Emergency Leave</option>
                                    <option value="maternity" {{ old('leave_type') == 'maternity' ? 'selected' : '' }}>🤱 Maternity Leave</option>
                                    <option value="paternity" {{ old('leave_type') == 'paternity' ? 'selected' : '' }}>👨👶 Paternity Leave</option>
                                    <option value="bereavement" {{ old('leave_type') == 'bereavement' ? 'selected' : '' }}>⚰️ Bereavement Leave</option>
                                    <option value="other" {{ old('leave_type') == 'other' ? 'selected' : '' }}>📝 Other</option>
                                </select>
                            </div>

                            <!-- Duration Display -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">📊 Duration</label>
                                <div id="durationDisplay" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 text-gray-600">
                                    Select dates to calculate duration
                                </div>
                            </div>

                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">📅 Start Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required min="{{ date('Y-m-d') }}">
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">📅 End Date</label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required min="{{ date('Y-m-d') }}">
                            </div>

                            <!-- Reason -->
                            <div class="md:col-span-2">
                                <label for="reason" class="block text-sm font-semibold text-gray-700 mb-2">📝 Reason (Brief)</label>
                                <input type="text" name="reason" id="reason" value="{{ old('reason') }}" maxlength="255" placeholder="Brief reason for leave..." class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">📄 Detailed Description</label>
                                <textarea name="description" id="description" maxlength="500" rows="4" placeholder="Provide detailed information about your leave request..." class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>{{ old('description') }}</textarea>
                                <div class="text-xs text-gray-500 mt-1">Maximum 500 characters</div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('dashboard_leave.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg font-medium transition duration-200">
                                Cancel
                            </a>
                            <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-8 py-3 rounded-lg font-medium transition duration-200 shadow-sm">
                                📤 Submit Leave Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const durationDisplay = document.getElementById('durationDisplay');

        function calculateDuration() {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                
                if (end >= start) {
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    durationDisplay.innerHTML = `<span class="text-blue-600 font-semibold">${diffDays} day(s)</span>`;
                    durationDisplay.classList.remove('text-gray-600');
                    durationDisplay.classList.add('text-blue-600');
                } else {
                    durationDisplay.innerHTML = '<span class="text-red-600">End date must be after start date</span>';
                    durationDisplay.classList.remove('text-gray-600', 'text-blue-600');
                    durationDisplay.classList.add('text-red-600');
                }
            } else {
                durationDisplay.innerHTML = 'Select dates to calculate duration';
                durationDisplay.classList.remove('text-blue-600', 'text-red-600');
                durationDisplay.classList.add('text-gray-600');
            }
        }

        function updateEndDateMin() {
            const startDate = startDateInput.value;
            if (startDate) {
                endDateInput.min = startDate;
            }
        }

        startDateInput.addEventListener('change', function() {
            updateEndDateMin();
            calculateDuration();
        });

        endDateInput.addEventListener('change', calculateDuration);

        // Form validation
        document.getElementById('leaveForm').addEventListener('submit', function(e) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (startDate < today) {
                e.preventDefault();
                alert('Start date cannot be in the past.');
                return false;
            }

            if (endDate < startDate) {
                e.preventDefault();
                alert('End date must be after or equal to start date.');
                return false;
            }
        });
    });
    </script>
</x-app-layout>
