<x-app-layout>
    <div class="mx-auto bg-white rounded-2xl shadow-lg p-6 h-full overflow-y-auto no-scrollbar">
        
        <h2 class="text-3xl font-extrabold text-gray-800 mb-0">🗓️ Mark Attendance</h2>

        <div class="flex justify-end mb-4 space-x-2">
            <form action="{{ route('dashboard_store.mark_all_attendence') }}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ now()->toDateString() }}">
                <input type="hidden" name="status" value="present">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Mark All Present</button>
            </form>
            <form action="{{ route('dashboard_store.mark_all_attendence') }}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ now()->toDateString() }}">
                <input type="hidden" name="status" value="absent">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Mark All Absent</button>
            </form>
        </div>

        <div class="overflow-y-auto no-scrollbar flex flex-col justify-between min-h-[75vh]">
            <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden" id="attendance-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">User Name</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">User ID</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Date</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Action</th>
                    </tr>
                </thead>
                <tbody id="attendance-tbody">
                    @forelse ($users as $user)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="px-5 py-4 text-gray-800 font-medium">{{ $user->name }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $user->id }}</td>
                            <td class="px-5 py-4 text-gray-600">
                                <form id="date-form-{{ $user->id }}" class="inline">
                                    <input type="date" name="date" value="{{ now()->toDateString() }}" class="border rounded px-2 py-1" />
                                </form>
                            </td>
                            <td class="px-5 py-4 flex space-x-2">
                                <form action="{{ route('dashboard_store.user_attendence') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <input type="hidden" name="status" value="present">
                                    <input type="hidden" name="date" id="present-date-{{ $user->id }}" value="{{ now()->toDateString() }}">
                                    <button type="submit" class="flex items-center space-x-1 text-green-600 hover:bg-green-50 border border-green-100 rounded-lg px-3 py-1 transition">
                                        <span>Present</span>
                                    </button>
                                </form>
                                <form action="{{ route('dashboard_store.user_attendence') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <input type="hidden" name="status" value="absent">
                                    <input type="hidden" name="date" id="absent-date-{{ $user->id }}" value="{{ now()->toDateString() }}">
                                    <button type="submit" class="flex items-center space-x-1 text-red-600 hover:bg-red-50 border border-red-100 rounded-lg px-3 py-1 transition">
                                        <span>Absent</span>
                                    </button>
                                </form>
                                <a href="{{ route('dashboard_details.user_attendence', ['id' => $user->id]) }}" 
                                   class="flex items-center space-x-1 text-blue-600 hover:bg-blue-50 border border-blue-100 rounded-lg px-3 py-1 transition">
                                    <span>View Details</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <script>
        // Sync date input with hidden fields for each user
        document.querySelectorAll('input[type="date"]').forEach(function(dateInput) {
            dateInput.addEventListener('change', function() {
                var userId = this.closest('tr').querySelector('td:nth-child(2)').textContent.trim();
                document.getElementById('present-date-' + userId).value = this.value;
                document.getElementById('absent-date-' + userId).value = this.value;
            });
        });
    </script>
</x-app-layout>
