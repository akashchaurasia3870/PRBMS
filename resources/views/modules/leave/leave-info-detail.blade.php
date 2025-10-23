<x-app-layout>
    <div class="max-w-6xl mx-auto">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">📝 Leave Request Details</h1>
                        <p class="text-gray-600 mt-1">View and manage leave request information</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('dashboard_leave.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                            ← Back to List
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Details -->
                    <div class="lg:col-span-2">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200">
                            <form id="leave-details-form" action="{{ route('dashboard_leave.edit_leave_info', $data->id) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $data->id }}">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">👤 Employee</label>
                                        <input type="text" value="{{ $data->name ?? 'N/A' }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50" disabled>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">🏷️ Leave Type</label>
                                        @php
                                            $typeIcons = ['sick' => '🤒', 'casual' => '👤', 'earned' => '🏖️', 'unpaid' => '📝'];
                                            $icon = $typeIcons[$data->leave_type] ?? '📝';
                                        @endphp
                                        <select name="leave_type" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50" disabled>
                                            @foreach(['sick', 'casual', 'earned', 'unpaid'] as $type)
                                                <option value="{{ $type }}" {{ $data->leave_type == $type ? 'selected' : '' }}>
                                                    {{ $typeIcons[$type] ?? '📝' }} {{ ucfirst($type) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">📅 Start Date</label>
                                        <input type="date" name="start_date" value="{{ \Carbon\Carbon::parse($data->start_date)->format('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50" disabled>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">📅 End Date</label>
                                        <input type="date" name="end_date" value="{{ \Carbon\Carbon::parse($data->end_date)->format('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50" disabled>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">📝 Reason</label>
                                        <input type="text" name="reason" value="{{ $data->reason }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50" disabled>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">📄 Description</label>
                                        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50" disabled>{{ $data->description ?? '' }}</textarea>
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                                    <button type="button" id="edit-btn" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                                        ✏️ Edit
                                    </button>
                                    <button type="button" id="cancel-btn" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg font-medium transition duration-200 hidden">
                                        ❌ Cancel
                                    </button>
                                    <button type="submit" id="save-btn" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium transition duration-200 hidden">
                                        ✅ Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Status & Actions Sidebar -->
                    <div class="space-y-6">
                        <!-- Status Card -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Status Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Current Status</label>
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

                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Duration</label>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($data->start_date)->diffInDays(\Carbon\Carbon::parse($data->end_date)) + 1 }} days
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Applied On</label>
                                    <div class="text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($data->created_at)->format('M d, Y') }}
                                    </div>
                                </div>

                                @if($data->rejection_reason)
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Rejection Reason</label>
                                        <div class="text-sm text-red-600 bg-red-50 p-2 rounded">
                                            {{ $data->rejection_reason }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions Card -->
                        @if($data->status === 'pending')
                            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">⚙️ Actions</h3>
                                <div class="space-y-3">
                                    <form action="{{ route('dashboard_leave.approve_leave_status', $data->id) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition duration-200" onclick="return confirm('Approve this leave request?')">
                                            ✅ Approve Leave
                                        </button>
                                    </form>
                                    <button onclick="openRejectModal()" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                                        ❌ Reject Leave
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Delete Action -->
                        <div class="bg-white border border-red-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-red-800 mb-4">⚠️ Danger Zone</h3>
                            <form action="{{ route('dashboard_leave.destroy_leave_info', $data->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition duration-200" onclick="return confirm('Are you sure you want to delete this leave request? This action cannot be undone.')">
                                    🗑️ Delete Request
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto">
            <form id="rejectForm" action="{{ route('dashboard_leave.reject_leave_status', $data->id) }}" method="POST">
                @csrf
                <div class="flex justify-between items-center px-6 py-4 border-b">
                    <h5 class="text-lg font-semibold">Reject Leave Request</h5>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeRejectModal()">&times;</button>
                </div>
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Reason for Rejection</label>
                        <textarea name="reason" class="w-full border border-gray-300 rounded-lg px-3 py-2" rows="3" required placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 px-6 py-4 border-t">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300" onclick="closeRejectModal()">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Reject</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const editBtn = document.getElementById('edit-btn');
        const cancelBtn = document.getElementById('cancel-btn');
        const saveBtn = document.getElementById('save-btn');
        const form = document.getElementById('leave-details-form');
        const inputs = form.querySelectorAll('input[name="leave_type"], input[name="start_date"], input[name="end_date"], input[name="reason"], textarea[name="description"]');

        editBtn.addEventListener('click', function() {
            inputs.forEach(input => {
                input.removeAttribute('disabled');
                input.classList.remove('bg-gray-50');
                input.classList.add('bg-white');
            });
            editBtn.classList.add('hidden');
            cancelBtn.classList.remove('hidden');
            saveBtn.classList.remove('hidden');
        });

        cancelBtn.addEventListener('click', function() {
            window.location.reload();
        });

        // Reject Modal Functions
        function openRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Make functions global
        window.openRejectModal = openRejectModal;
        window.closeRejectModal = closeRejectModal;
    </script>
</x-app-layout>
