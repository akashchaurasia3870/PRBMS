<x-app-layout>
    <div class="mx-auto bg-white rounded-2xl shadow-2xl p-0 h-full overflow-y-auto no-scrollbar transition-all duration-300">
        
        <div class="flex flex-row md:flex-col justify-between md:justify-end w-full my-6">
            <h2 class="text-4xl font-extrabold text-indigo-800 mb-0 flex items-center gap-2">
                <span class="text-3xl">📝</span> Leave Requests
            </h2>
        </div>

        <div class="overflow-y-auto no-scrollbar flex flex-col justify-between min-h-[70vh]">
            <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden shadow-lg bg-gradient-to-br from-gray-50 to-white" id="leave-request-table">
                <thead class="bg-indigo-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">User Name</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">User ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Applied At</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">From Date</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">To Date</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody id="leave-request-tbody">
                    @forelse ($leaves as $leave)
                        <tr class="border-b border-gray-100 hover:bg-indigo-50 transition duration-200">
                            <td class="px-6 py-4 text-gray-900 font-semibold">{{ $leave->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $leave->user_id ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ \Carbon\Carbon::parse($leave->created_at)->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if($leave->status === 'approved')
                                    <span class="inline-block px-3 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full">Approved</span>
                                @elseif($leave->status === 'rejected')
                                    <span class="inline-block px-3 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full">Rejected</span>
                                @else
                                    <span class="inline-block px-3 py-1 text-xs font-bold text-yellow-700 bg-yellow-100 rounded-full">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 flex flex-wrap gap-2">
                                <a href="{{ route('dashboard_leave.get_leave_info', $leave->id) }}" class="px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 font-semibold transition">View</a> 
                                <form action="{{ route('dashboard_leave.destroy_leave_info', $leave->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this leave request?');" class="inline-block">
                                    @csrf
                                    @method("DELETE")
                                    <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 font-semibold transition">Delete</button>
                                </form>
                                <form action="{{ route('dashboard_leave.approve_leave_status', $leave->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve this leave request?');" class="inline-block">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 font-semibold transition">Approve</button>
                                </form>
                                <!-- Reject Button triggers modal -->
                                <button type="button" onclick="openRejectModal({{ $leave->id }})" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 font-semibold transition">Reject</button>

                                <!-- Modal -->
                                <div id="reject-modal-{{ $leave->id }}" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                                    <div class="bg-white rounded-lg shadow-2xl p-8 w-full max-w-md border-2 border-red-200">
                                        <h3 class="text-xl font-bold mb-4 text-red-700">Reject Leave Request</h3>
                                        <form action="{{ route('dashboard_leave.reject_leave_status', $leave->id) }}" method="POST" onsubmit="return submitRejectForm(event, {{ $leave->id }})">
                                            @csrf
                                            <label for="reason-{{ $leave->id }}" class="block text-sm font-medium text-gray-700 mb-2">Reason for rejection:</label>
                                            <textarea id="reason-{{ $leave->id }}" name="reason" required class="w-full border border-red-300 rounded p-2 mb-4 focus:ring-2 focus:ring-red-200"></textarea>
                                            <div class="flex justify-end space-x-2">
                                                <button type="button" onclick="closeRejectModal({{ $leave->id }})" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 font-semibold">Cancel</button>
                                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 font-semibold">Reject</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500 font-semibold bg-gray-50">No leave requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="my-4 flex justify-end ml-8">
                {{ $leaves->links() }}
            </div>
        </div>
    </div>
    <script>
            function openRejectModal(id) {
                document.getElementById('reject-modal-' + id).classList.remove('hidden');
            }
            function closeRejectModal(id) {
                document.getElementById('reject-modal-' + id).classList.add('hidden');
            }
            function submitRejectForm(event, id) {
                const reason = document.getElementById('reason-' + id).value.trim();
                if (!reason) {
                    alert('Please provide a reason for rejection.');
                    return false;
                }
                return true;
            }
    </script>
</x-app-layout>
