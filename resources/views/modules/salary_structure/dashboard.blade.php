<x-app-layout>
    <div class="">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">💼 Salary Structure Dashboard</h1>
                            <p class="text-gray-600 mt-1">Overview of employee salary structures and compensation</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('dashboard_salary.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                ➕ Create Structure
                            </a>
                            <a href="{{ route('dashboard_salary.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                📋 View All Structures
                            </a>
                        </div>
                    </div>

                    <!-- Quick Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Total Structures -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm font-medium">Total Structures</p>
                                    <p class="text-3xl font-bold">{{ $total_structures ?? 0 }}</p>
                                </div>
                                <div class="text-4xl opacity-80">💼</div>
                            </div>
                        </div>

                        <!-- Average Salary -->
                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm font-medium">Average Salary</p>
                                    <p class="text-2xl font-bold">₹{{ number_format($avg_basic_salary ?? 0, 0) }}</p>
                                </div>
                                <div class="text-4xl opacity-80">📊</div>
                            </div>
                        </div>

                        <!-- Total Budget -->
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm font-medium">Total Budget</p>
                                    <p class="text-2xl font-bold">₹{{ number_format($total_salary_budget ?? 0, 0) }}</p>
                                </div>
                                <div class="text-4xl opacity-80">💰</div>
                            </div>
                        </div>

                        <!-- Highest Salary -->
                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-orange-100 text-sm font-medium">Highest Salary</p>
                                    <p class="text-2xl font-bold">₹{{ number_format($highest_salary ?? 0, 0) }}</p>
                                </div>
                                <div class="text-4xl opacity-80">🏆</div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Salary Range Distribution -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                📈 Salary Range Distribution
                            </h3>
                            @if($salary_ranges && $salary_ranges->count() > 0)
                                <div class="space-y-4">
                                    @php
                                        $maxCount = $salary_ranges->max('count');
                                        $colors = [
                                            'Below 30K' => 'bg-red-500',
                                            '30K-50K' => 'bg-yellow-500',
                                            '50K-75K' => 'bg-blue-500',
                                            '75K-100K' => 'bg-green-500',
                                            'Above 100K' => 'bg-purple-500'
                                        ];
                                    @endphp
                                    @foreach($salary_ranges as $range)
                                        @php
                                            $percentage = $maxCount > 0 ? ($range->count / $maxCount) * 100 : 0;
                                            $color = $colors[$range->salary_range] ?? 'bg-gray-500';
                                        @endphp
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3 flex-1">
                                                <span class="text-sm font-medium text-gray-700 w-20">{{ $range->salary_range }}</span>
                                                <div class="flex-1 bg-gray-200 rounded-full h-3">
                                                    <div class="{{ $color }} h-3 rounded-full" style="width: {{ $percentage }}%"></div>
                                                </div>
                                            </div>
                                            <div class="text-right ml-4">
                                                <div class="text-sm font-semibold text-gray-900">{{ $range->count }} employees</div>
                                                <div class="text-xs text-gray-500">Avg: ₹{{ number_format($range->avg_salary, 0) }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <div class="text-4xl mb-2">📊</div>
                                    <p>No salary data available</p>
                                </div>
                            @endif
                        </div>

                        <!-- Recent Salary Structures -->
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                🕒 Recent Salary Structures
                            </h3>
                            @if($recent_structures && $recent_structures->count() > 0)
                                <div class="space-y-3 max-h-80 overflow-y-auto">
                                    @foreach($recent_structures as $structure)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900">{{ $structure->user->name ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500">
                                                    Basic: ₹{{ number_format($structure->basic_salary, 0) }} • 
                                                    Gross: ₹{{ number_format($structure->gross_salary, 0) }}
                                                </div>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $structure->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3 text-center">
                                    <a href="{{ route('dashboard_salary.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                        View all structures →
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <div class="text-4xl mb-2">💼</div>
                                    <p>No salary structures found</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Salary Components Breakdown -->
                    <div class="mt-8">
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                🧮 Salary Components Overview
                            </h3>
                            @if($recent_structures && $recent_structures->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Basic Salary</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HRA</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DA</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Other</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gross</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($recent_structures->take(5) as $structure)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ $structure->user->name ?? 'N/A' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        ₹{{ number_format($structure->basic_salary, 0) }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        ₹{{ number_format($structure->hra, 0) }}
                                                        <span class="text-xs text-gray-400">({{ $structure->hra_percentage }}%)</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        ₹{{ number_format($structure->da, 0) }}
                                                        <span class="text-xs text-gray-400">({{ $structure->da_percentage }}%)</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        ₹{{ number_format($structure->other_allowance, 0) }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        ₹{{ number_format($structure->gross_salary, 0) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <div class="text-4xl mb-2">🧮</div>
                                    <p>No salary structures to display</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>