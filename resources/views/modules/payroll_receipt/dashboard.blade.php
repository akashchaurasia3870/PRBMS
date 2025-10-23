<x-app-layout>
    <div class="">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">💰 Payroll Dashboard</h1>
                            <p class="text-gray-600 mt-1">Overview of payroll statistics and management</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('dashboard_payroll.generateForm') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                ➕ Generate Payroll
                            </a>
                            <a href="{{ route('dashboard_payroll.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                📋 View All Payrolls
                            </a>
                        </div>
                    </div>

                    <!-- Quick Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Total Payrolls -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm font-medium">Total Payrolls</p>
                                    <p class="text-3xl font-bold">{{ $total_payrolls ?? 0 }}</p>
                                </div>
                                <div class="text-4xl opacity-80">💰</div>
                            </div>
                        </div>

                        <!-- Current Month -->
                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm font-medium">This Month</p>
                                    <p class="text-3xl font-bold">{{ $current_month_payrolls ?? 0 }}</p>
                                </div>
                                <div class="text-4xl opacity-80">📅</div>
                            </div>
                        </div>

                        <!-- Paid Payrolls -->
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm font-medium">Paid</p>
                                    <p class="text-3xl font-bold">{{ $paid_payrolls ?? 0 }}</p>
                                </div>
                                <div class="text-4xl opacity-80">✅</div>
                            </div>
                        </div>

                        <!-- Pending Payrolls -->
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-yellow-100 text-sm font-medium">Pending</p>
                                    <p class="text-3xl font-bold">{{ $pending_payrolls ?? 0 }}</p>
                                </div>
                                <div class="text-4xl opacity-80">⏳</div>
                            </div>
                        </div>
                    </div>

                    <!-- Salary Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-indigo-100 text-sm font-medium">Total Salary Paid</p>
                                    <p class="text-2xl font-bold">₹{{ number_format($total_salary_paid ?? 0, 2) }}</p>
                                </div>
                                <div class="text-4xl opacity-80">💵</div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-teal-100 text-sm font-medium">This Month Salary</p>
                                    <p class="text-2xl font-bold">₹{{ number_format($current_month_salary ?? 0, 2) }}</p>
                                </div>
                                <div class="text-4xl opacity-80">📊</div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Monthly Statistics -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                📈 Monthly Statistics ({{ now()->year }})
                            </h3>
                            @if($monthly_stats && $monthly_stats->count() > 0)
                                <div class="space-y-3">
                                    @foreach($monthly_stats as $stat)
                                        @php
                                            $monthName = date('F', mktime(0, 0, 0, $stat->month, 1));
                                            $maxAmount = $monthly_stats->max('total_amount');
                                            $percentage = $maxAmount > 0 ? ($stat->total_amount / $maxAmount) * 100 : 0;
                                        @endphp
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <span class="text-sm font-medium text-gray-700 w-20">{{ $monthName }}</span>
                                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm font-semibold text-gray-900">{{ $stat->count }} payrolls</div>
                                                <div class="text-xs text-gray-500">₹{{ number_format($stat->total_amount, 0) }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <div class="text-4xl mb-2">📊</div>
                                    <p>No payroll data for this year</p>
                                </div>
                            @endif
                        </div>

                        <!-- Pending Payrolls -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                ⏳ Pending Payrolls
                            </h3>
                            @if($pending_payrolls_list && $pending_payrolls_list->count() > 0)
                                <div class="space-y-3 max-h-80 overflow-y-auto">
                                    @foreach($pending_payrolls_list as $payroll)
                                        <div class="flex items-center justify-between p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900">{{ $payroll->user->name ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ date('F', mktime(0, 0, 0, $payroll->month, 1)) }} {{ $payroll->year }} • 
                                                    ₹{{ number_format($payroll->net_salary, 2) }}
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('dashboard_payroll.show', $payroll->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                                    👁️
                                                </a>
                                                <form action="{{ route('dashboard_payroll.markAsPaid', $payroll->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-800 text-sm" onclick="return confirm('Mark as paid?')">
                                                        ✅
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($pending_payrolls_list->count() >= 10)
                                    <div class="mt-3 text-center">
                                        <a href="{{ route('dashboard_payroll.index', ['status' => 'generated']) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                            View all pending payrolls →
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <div class="text-4xl mb-2">✅</div>
                                    <p>No pending payrolls</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="mt-8">
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                🕒 Recent Payroll Activity
                            </h3>
                            @if($recent_payrolls && $recent_payrolls->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recent_payrolls as $payroll)
                                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    @if($payroll->status === 'paid')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            ✅ Paid
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            ⏳ Generated
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <div class="text-sm font-medium text-gray-900">{{ $payroll->user->name ?? 'N/A' }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ date('F', mktime(0, 0, 0, $payroll->month, 1)) }} {{ $payroll->year }} • 
                                                        ₹{{ number_format($payroll->net_salary, 2) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <div class="text-xs text-gray-500">
                                                    {{ $payroll->created_at->diffForHumans() }}
                                                </div>
                                                <a href="{{ route('dashboard_payroll.show', $payroll->id) }}" class="text-blue-600 hover:text-blue-800">
                                                    👁️
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4 text-center">
                                    <a href="{{ route('dashboard_payroll.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                        View all payroll records →
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <div class="text-4xl mb-2">💰</div>
                                    <p>No recent payroll activity</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>