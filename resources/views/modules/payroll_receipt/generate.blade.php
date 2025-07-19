<x-app-layout>
    {{-- <form action="{{ route('dashboard_payroll.generatePayroll') }}" method="POST" class="space-y-4">
        @csrf
        <x-generate-receipt :users="$users" />
    </form> --}}

    <form id="generatePayrollForm" action="{{ route('dashboard_payroll.generatePayroll') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="user_id" id="user_id">
        <input type="hidden" name="month" id="month">
        <input type="hidden" name="year" id="year">
    </form>


    <div class="mx-auto bg-white rounded-2xl shadow-lg p-6 h-full overflow-y-auto no-scrollbar"
     x-data="payrollFilter()" x-init="initFilters()">

        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">📄 Generate Payroll Receipts</h2>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('dashboard_payroll.generateForm') }}" id="filterForm"
            class="flex flex-wrap gap-3 mb-6">

            <select name="month"
                    class="border-gray-300 p-2 rounded-lg w-32 focus:ring focus:ring-blue-100"
                    id="monthFilter">
                @php
                    $selectedMonth = request('month') ?? now()->month;
                @endphp
                @foreach ([
                    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                ] as $num => $monthName)
                    <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>
                        {{ $monthName }}
                    </option>
                @endforeach
            </select>

            <input type="number" name="year" placeholder="Year" value="{{ request('year', 2025) }}"
                class="border-gray-300 p-2 rounded-lg w-32 focus:ring focus:ring-blue-100"
                min="2025" max="2025" id="yearFilter">

            <input type="text" name="search" placeholder="Search by username"
                value="{{ request('search') }}"
                class="border-gray-300 p-2 rounded-lg w-48 focus:ring focus:ring-blue-100"
                x-model="search"
                @input="debouncedSearch">

        </form>

        {{-- Payroll Table --}}
        <div class="overflow-y-auto no-scrollbar flex flex-col justify-between min-h-[75vh]">
            <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden" id="payroll-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Working</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Present</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Leave</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Day</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Gross Salary</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Net Salary</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="px-5 py-4 text-gray-800">{{ $record->name }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $record->total_days }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $record->present_days }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $record->leave_days }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $record->per_day_salary }}</td>
                            <td class="px-5 py-4 text-gray-700">₹ {{ number_format($record->gross_salary, 2) }}</td>
                            <td class="px-5 py-4 text-gray-700">₹ {{ number_format($record->net_salary, 2) }}</td>
                            <td class="px-5 py-4">
                                @if($record->net_salary>0)
                                    <button type="button"
                                        onclick="submitPayrollForm({{ $record->user_id }}, {{ $record->month }}, {{ $record->year }})"
                                        class="text-blue-600 hover:underline font-semibold">
                                        Generate
                                    </button>
                                @else 
                                    <span>-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-6 text-gray-500">No payroll records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-6">
                {{ $records->links() }}
            </div>
        </div>

    </div>

    {{-- AlpineJS Logic --}}
    <script>
        function payrollFilter() {
            return {
                search: '{{ request("search") }}',
                timeout: null,

                debouncedSearch() {
                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => {
                        document.getElementById('filterForm').submit();
                    }, 500);
                },

                initFilters() {
                    // Attach native JS change events for select and number inputs
                    document.getElementById('monthFilter').addEventListener('change', () => {
                        document.getElementById('filterForm').submit();
                    });
                    document.getElementById('yearFilter').addEventListener('change', () => {
                        document.getElementById('filterForm').submit();
                    });
                    document.getElementById('statusFilter').addEventListener('change', () => {
                        document.getElementById('filterForm').submit();
                    });
                }
            }
        }

        function submitPayrollForm(userId, month, year) {
        document.getElementById('user_id').value = userId;
        document.getElementById('month').value = month;
        document.getElementById('year').value = year;
        document.getElementById('generatePayrollForm').submit();
        }


    </script>
</x-app-layout>
