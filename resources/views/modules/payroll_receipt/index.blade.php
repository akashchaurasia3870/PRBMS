<x-app-layout>
<div class="mx-auto bg-white rounded-2xl shadow-lg p-6 h-full overflow-y-auto no-scrollbar"
     x-data="payrollFilter()" x-init="initFilters()">

    <h2 class="text-3xl font-extrabold text-gray-800 mb-6">📄 Payroll Receipts</h2>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('dashboard_payroll.index') }}" id="filterForm"
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

        <select name="status"
                class="border-gray-300 p-2 rounded-lg w-40 focus:ring focus:ring-blue-100"
                id="statusFilter">
            <option value="">All Status</option>
            <option value="generated" {{ request('status') == 'generated' ? 'selected' : '' }}>Generated</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
        </select>

    </form>

    {{-- Payroll Table --}}
    <div class="overflow-y-auto no-scrollbar flex flex-col justify-between min-h-[75vh]">
        <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden" id="payroll-table">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Month/Year</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Working</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Present</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Leave</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Net Salary</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($receipts as $receipt)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="px-5 py-4 text-gray-800">{{ $receipt->name }}</td>
                        <td class="px-5 py-4 text-gray-700">{{ $receipt->month }}/{{ $receipt->year }}</td>
                        <td class="px-5 py-4 text-gray-700">{{ $receipt->total_working_days }}</td>
                        <td class="px-5 py-4 text-gray-700">{{ $receipt->present_days }}</td>
                        <td class="px-5 py-4 text-gray-700">{{ $receipt->leave_days }}</td>
                        <td class="px-5 py-4 text-gray-700">₹ {{ number_format($receipt->net_salary, 2) }}</td>
                        <td class="px-5 py-4">
                            <span class="px-2 py-1 rounded text-xs font-semibold text-white bg-yellow-500">
                                {{ ucfirst($receipt->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 flex space-x-2">
                            <span class="px-2 py-1 rounded text-xs font-semibold text-white bg-blue-600">
                                <a href="{{ route('dashboard_payroll.show', $receipt->id) }}"
                                    class="text-white hover:underline">View</a>
                            </span>
                            <span class="px-2 py-1 rounded text-xs font-semibold text-white bg-red-600">
                                <a href="{{ route('dashboard_payroll.edit', $receipt->id) }}"
                               class="text-white hover:underline">Edit</a>
                            </span>
                            {{-- <span class="px-2 py-1 rounded text-xs font-semibold text-white bg-blue-600">
                                <form action="{{ route('dashboard_payroll.markAsPaid', $receipt->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-white hover:underline">Pay</button>
                                </form>
                            </span> --}}
                            <span class="px-2 py-1 rounded text-xs font-semibold text-white bg-blue-600">
                                {{-- Delete --}}
                                <form action="{{ route('dashboard_payroll.destroy', $receipt->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this payroll?')">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="text-white hover:underline">Delete</button>
                                </form>
                            </span>

                            
                            
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
            {{ $receipts->appends(request()->except('page'))->links() }}
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
</script>
</x-app-layout>
