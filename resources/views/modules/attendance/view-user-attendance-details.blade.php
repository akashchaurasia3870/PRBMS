<x-app-layout>
    <div class="mx-auto bg-white rounded-2xl shadow-lg p-6 h-full overflow-y-auto no-scrollbar">
        
        <div class="flex flex-row md:flex-col justify-between md:justify-end w-full">
            @php
                $firstUser = $users->first();
            @endphp
            <h2 class="text-3xl font-extrabold text-gray-800 mb-0">
                🗓️ Attendance Details for {{ $firstUser ? $firstUser->name : 'User' }}
            </h2>
        </div>

        <div class="overflow-y-auto no-scrollbar flex flex-col justify-between min-h-[75vh]">
            <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden" id="attendance-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Year</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Month</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Present Count</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Absent Count</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Total Working Days</th>
                    </tr>
                </thead>
                <tbody id="attendance-tbody">
                    @forelse ($users as $user)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="px-5 py-4 text-gray-800 font-medium">{{ $user->year }}</td>
                            <td class="px-5 py-4 text-gray-700">
                                {{ \Carbon\Carbon::create()->month($user->month)->format('F') }}
                            </td>
                            <td class="px-5 py-4 text-gray-700">{{ $user->presentDays }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $user->absentDays }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $user->totalWorkingDays }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">No attendance data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
