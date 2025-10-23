<x-app-layout>
    <div class="">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">💰 Payroll Management</h1>
                            <p class="text-gray-600 mt-1">Manage employee payroll records and payments</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('dashboard_payroll.generateForm') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                ➕ Generate Payroll
                            </a>
                            <a href="{{ route('dashboard_payroll.dashboard') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                📊 Dashboard
                            </a>
                        </div>
                    </div>

                    <!-- Enhanced Filters -->
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-6 rounded-xl mb-6 border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                🔍 Advanced Search & Filters
                            </h3>
                        </div>
                        
                        <form method="GET" action="{{ route('dashboard_payroll.index') }}" id="filterForm">
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-2">🔍 Search</label>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by employee name..." class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-2">📅 Month</label>
                                    <select name="month" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @php $selectedMonth = request('month') ?? now()->month; @endphp
                                        @foreach ([
                                            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                                        ] as $num => $monthName)
                                            <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $monthName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-2">📅 Year</label>
                                    <input type="number" name="year" value="{{ request('year', now()->year) }}" min="2020" max="2030" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-2">📊 Status</label>
                                    <select name="status" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">All Status</option>
                                        <option value="generated" {{ request('status') == 'generated' ? 'selected' : '' }}>⏳ Generated</option>
                                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>✅ Paid</option>
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 shadow-sm">
                                        🔍 Search
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap gap-3 items-center">
                                <a href="{{ route('dashboard_payroll.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition duration-200 border border-gray-300">
                                    ❌ Clear All
                                </a>
                                <div class="text-xs text-gray-500 ml-auto">
                                    {{ $receipts->total() }} results found
                                </div>
                            </div>
                        </form>
                    </div>


                    <!-- Desktop Table -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salary</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($receipts as $receipt)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $receipt->user->name ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">ID: {{ $receipt->user_id }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ date('F Y', mktime(0, 0, 0, $receipt->month, 1, $receipt->year)) }}</div>
                                            <div class="text-sm text-gray-500">{{ $receipt->month }}/{{ $receipt->year }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $receipt->present_days }}/{{ $receipt->total_working_days }} days</div>
                                            <div class="text-sm text-gray-500">{{ $receipt->leave_days }} leaves</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">₹{{ number_format($receipt->net_salary, 2) }}</div>
                                            @if($receipt->total_salary)
                                                <div class="text-sm text-gray-500">Gross: ₹{{ number_format($receipt->total_salary, 2) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($receipt->status === 'paid')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    ✅ Paid
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    ⏳ Generated
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('dashboard_payroll.show', $receipt->id) }}" class="text-blue-600 hover:text-blue-900" title="View">👁️</a>
                                                <a href="{{ route('dashboard_payroll.edit', $receipt->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">✏️</a>
                                                @if($receipt->status !== 'paid')
                                                    <form action="{{ route('dashboard_payroll.markAsPaid', $receipt->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-900" title="Mark as Paid" onclick="return confirm('Mark as paid?')">✅</button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('dashboard_payroll.destroy', $receipt->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">🗑️</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="text-gray-500">
                                                <div class="text-6xl mb-4">💰</div>
                                                <h3 class="text-lg font-medium mb-2">No payroll records found</h3>
                                                <p class="text-sm">Generate payroll or adjust your filters.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="lg:hidden space-y-4">
                        @forelse($receipts as $receipt)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $receipt->user->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ date('F Y', mktime(0, 0, 0, $receipt->month, 1, $receipt->year)) }}</div>
                                    </div>
                                    <div>
                                        @if($receipt->status === 'paid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">✅ Paid</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">⏳ Generated</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600 mb-2">
                                    Attendance: {{ $receipt->present_days }}/{{ $receipt->total_working_days }} days
                                </div>
                                <div class="text-lg font-semibold text-gray-900 mb-3">
                                    ₹{{ number_format($receipt->net_salary, 2) }}
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="text-xs text-gray-500">
                                        {{ $receipt->leave_days }} leave days
                                    </div>
                                    <div class="flex space-x-3">
                                        <a href="{{ route('dashboard_payroll.show', $receipt->id) }}" class="text-blue-600">👁️</a>
                                        <a href="{{ route('dashboard_payroll.edit', $receipt->id) }}" class="text-indigo-600">✏️</a>
                                        @if($receipt->status !== 'paid')
                                            <form action="{{ route('dashboard_payroll.markAsPaid', $receipt->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600" onclick="return confirm('Mark as paid?')">✅</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('dashboard_payroll.destroy', $receipt->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                            @csrf
                                            <button type="submit" class="text-red-600">🗑️</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="text-gray-500">
                                    <div class="text-6xl mb-4">💰</div>
                                    <h3 class="text-lg font-medium mb-2">No payroll records found</h3>
                                    <p class="text-sm mb-4">Generate payroll to get started.</p>
                                    <a href="{{ route('dashboard_payroll.generateForm') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                        Generate Payroll
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($receipts->hasPages())
                        <div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                            <div class="text-sm text-gray-700">
                                Showing {{ $receipts->firstItem() }} to {{ $receipts->lastItem() }} of {{ $receipts->total() }} results
                            </div>
                            <div class="flex justify-center">
                                {{ $receipts->appends(request()->query())->links('pagination::tailwind') }}
                            </div>
                        </div>
                    @endif
                </div>
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
