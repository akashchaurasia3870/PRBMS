<x-app-layout>
    <div class="">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">📝 Leave Request Details</h1>
                            <p class="text-gray-600 mt-1">View and manage leave request information</p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('dashboard_leave.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                ← Back to List
                            </a>
                            @if($data->status === 'pending')
                                <button onclick="openEditMode()" id="editBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                    ✏️ Edit
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    @if(session('success') || isset($message))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') ?? $message }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Main Content -->
                        <div class="lg:col-span-2">
                            <form id="leaveForm" action="{{ route('dashboard_leave.edit_leave_info', $data->id) }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="id" value="{{ $data->id }}">

                                <!-- Employee Information -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">👤 Employee Information</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Employee Name</label>
                                            <input type="text" value="{{ $data->name }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100" disabled>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Employee ID</label>
                                            <input type="text" value="{{ $data->user_id }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100" disabled>
                                        </div>
                                    </div>
                                </div>

                                <!-- Leave Details -->
                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Leave Details</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type</label>
                                            <select name="leave_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 form-field" disabled>
                                                <option value="sick" {{ $data->leave_type == 'sick' ? 'selected' : '' }}>🤒 Sick Leave</option>
                                                <option value="casual" {{ $data->leave_type == 'casual' ? 'selected' : '' }}>🏖️ Casual Leave</option>
                                                <option value="annual" {{ $data->leave_type == 'annual' ? 'selected' : '' }}>🌴 Annual Leave</option>
                                                <option value="maternity" {{ $data->leave_type == 'maternity' ? 'selected' : '' }}>👶 Maternity Leave</option>
                                                <option value="paternity" {{ $data->leave_type == 'paternity' ? 'selected' : '' }}>👨👶 Paternity Leave</option>
                                                <option value="emergency" {{ $data->leave_type == 'emergency' ? 'selected' : '' }}>🚨 Emergency Leave</option>
                                                <option value="unpaid" {{ $data->leave_type == 'unpaid' ? 'selected' : '' }}>💸 Unpaid Leave</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                            <div class="mt-2">
                                                @if($data->status === 'approved')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                        ✅ Approved
                                                    </span>
                                                @elseif($data->status === 'rejected')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                        ❌ Rejected
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                        ⏳ Pending
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                            <input type="date" name="start_date" value="{{ \Carbon\Carbon::parse($data->start_date)->format('Y-m-d') }}" 
                                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 form-field" disabled>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                            <input type="date" name="end_date" value="{{ \Carbon\Carbon::parse($data->end_date)->format('Y-m-d') }}" 
                                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 form-field" disabled>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                                        <input type="text" name="reason" value="{{ $data->reason }}" 
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 form-field" disabled>
                                    </div>

                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <textarea name="description" rows="3" 
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 form-field" disabled>{{ $data->description ?? '' }}</textarea>
                                    </div>
                                </div>

                                <!-- Action Buttons (Edit Mode) -->
                                <div id="editActions" class="hidden flex flex-col sm:flex-row gap-3">
                                    <button type="submit" class="flex-1 bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                                        💾 Save Changes
                                    </button>
                                    <button type="button" onclick="cancelEdit()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg font-medium transition duration-200">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Sidebar -->
                        <div class="space-y-6">
                            <!-- Duration Info -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-800 mb-2">⏱️ Duration</h4>
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ \Carbon\Carbon::parse($data->start_date)->diffInDays(\Carbon\Carbon::parse($data->end_date)) + 1 }} days
                                </div>
                                <div class="text-sm text-blue-600 mt-1">
                                    {{ \Carbon\Carbon::parse($data->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($data->end_date)->format('M d, Y') }}
                                </div>
                            </div>

                            <!-- Application Info -->
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-800 mb-3">📅 Application Info</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Applied:</span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($data->created_at)->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Time:</span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }}</span>
                                    </div>
                                    @if($data->updated_at != $data->created_at)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Last Updated:</span>
                                            <span class="font-medium">{{ \Carbon\Carbon::parse($data->updated_at)->format('M d, Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            @if($data->status === 'pending')
                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-800 mb-3">⚡ Quick Actions</h4>
                                    <div class="space-y-2">
                                        <form action="{{ route('dashboard_leave.approve_leave_status', $data->id) }}" method="POST" class="w-full" onsubmit="return confirm('Approve this leave request?')">
                                            @csrf
                                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                                                ✅ Approve
                                            </button>
                                        </form>
                                        <button onclick="openRejectModal()" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                                            ❌ Reject
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <!-- Rejection Reason -->
                            @if($data->status === 'rejected' && isset($data->rejection_reason))
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-red-800 mb-2">❌ Rejection Reason</h4>
                                    <p class="text-sm text-red-700">{{ $data->rejection_reason }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center">
        <div class="bg-white rounded shadow-lg w-full max-w-md mx-auto">
            <form action="{{ route('dashboard_leave.reject_leave_status', $data->id) }}" method="POST">
                @csrf
                <div class="flex justify-between items-center px-6 py-4 border-b">
                    <h5 class="text-lg font-semibold">Reject Leave Request</h5>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeRejectModal()">&times;</button>
                </div>
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Reason for Rejection</label>
                        <textarea name="reason" class="w-full border rounded px-3 py-2" rows="3" required placeholder="Please provide a reason..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 px-6 py-4 border-t">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300" onclick="closeRejectModal()">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Reject</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openEditMode() {
        // Enable form fields
        document.querySelectorAll('.form-field').forEach(field => {
            field.disabled = false;
            field.classList.remove('bg-gray-100');
        });
        
        // Show/hide buttons
        document.getElementById('editBtn').classList.add('hidden');
        document.getElementById('editActions').classList.remove('hidden');
    }

    function cancelEdit() {
        location.reload();
    }

    function openRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
    </script>
</x-app-layout>