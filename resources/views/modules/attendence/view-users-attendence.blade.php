<x-app-layout>
    <div class="mx-auto bg-white rounded-2xl shadow-lg p-6 h-full overflow-y-auto no-scrollbar">
        
        <div class="flex flex-row md:flex-col justify-between md:justify-end w-full">


            <h2 class="text-3xl font-extrabold text-gray-800 mb-0">🗓️ View Attendance</h2>

            {{-- Month, Year & Search Filter --}}
            <form action="{{ route('dashboard_list.user_attendence') }}" method="GET" class="flex items-center mb-4 space-x-2" id="attendance-filter-form">
                {{-- @csrf --}}
                <label>
                    <select name="month" class="border rounded px-2 py-1" onchange="document.getElementById('attendance-filter-form').submit()">
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ (request('month', now()->month) == $m) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label>
                    <select name="year" class="border rounded px-8 py-1" onchange="document.getElementById('attendance-filter-form').submit()">
                        @php
                            $currentYear = now()->year;
                        @endphp
                        @for($y = $currentYear; $y >= $currentYear - 5; $y--)
                            <option value="{{ $y }}" {{ (request('year', $currentYear) == $y) ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </label>
                <label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search ..."
                        class="border rounded px-2 py-1"
                        oninput="document.getElementById('attendance-filter-form').submit()"
                        autocomplete="off"
                    >
                </label>
            </form>
        </div>


        <div class="overflow-y-auto no-scrollbar flex flex-col justify-between min-h-[75vh]">
            <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden" id="attendance-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">User Name</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">User ID</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Present Days</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Absent Days</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Total Working Days</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Action</th>
                    </tr>
                </thead>
                <tbody id="attendance-tbody">
                    @forelse ($users as $user)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="px-5 py-4 text-gray-800 font-medium">{{ $user->name }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $user->id }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $user->presentDays }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $user->absentDays }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $user->totalWorkingDays }}</td>
                            <td class="px-5 py-4">
                                @if(!empty($user->id))
                                    <a href="{{ route('dashboard_details.user_attendence', ['id' => $user->id]) }}" class="text-blue-500 hover:text-blue-700">View Details</a>
                                @else
                                    <span class="text-gray-400 cursor-not-allowed">View Details</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-500">No users found.</td>
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
