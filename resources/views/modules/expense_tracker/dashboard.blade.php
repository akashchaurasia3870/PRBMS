<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">📊 Expense Dashboard</h1>
                        <p class="text-gray-600 mt-1">Overview of your expense management</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('expense.v1.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                            📋 View All Expenses
                        </a>
                        <a href="{{ route('expense.v1.new') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                            ➕ Add Expense
                        </a>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="text-blue-600 text-3xl mr-4">💰</div>
                            <div>
                                <p class="text-sm text-blue-600 font-medium">Total Expenses</p>
                                <p class="text-2xl font-bold text-blue-800">${{ number_format($data['total_expenses'] ?? 0, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="text-green-600 text-3xl mr-4">📊</div>
                            <div>
                                <p class="text-sm text-green-600 font-medium">Total Records</p>
                                <p class="text-2xl font-bold text-green-800">{{ $data['expense_count'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="text-yellow-600 text-3xl mr-4">📈</div>
                            <div>
                                <p class="text-sm text-yellow-600 font-medium">This Month</p>
                                <p class="text-2xl font-bold text-yellow-800">${{ number_format(collect($data['expenses_by_type'] ?? [])->sum('total_amount'), 2) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="text-purple-600 text-3xl mr-4">🏷️</div>
                            <div>
                                <p class="text-sm text-purple-600 font-medium">Categories</p>
                                <p class="text-2xl font-bold text-purple-800">{{ count($data['expenses_by_type'] ?? []) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Expenses -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">🕒 Recent Expenses</h3>
                        @if(isset($data['recent_expenses']) && $data['recent_expenses']->count() > 0)
                            <div class="space-y-3">
                                @foreach($data['recent_expenses'] as $expense)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ Str::limit($expense->description, 30) }}</p>
                                            <p class="text-xs text-gray-500">{{ $expense->type }} • {{ $expense->expense_date ? \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') : 'N/A' }}</p>
                                        </div>
                                        <span class="text-lg font-bold text-green-600">${{ number_format($expense->amount, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('expense.v1.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View All Expenses →
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-gray-400 text-4xl mb-2">📊</div>
                                <p class="text-gray-500">No expenses recorded yet</p>
                                <a href="{{ route('expense.v1.new') }}" class="inline-block mt-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                                    Add First Expense
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Expenses by Type -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">🏷️ Expenses by Type</h3>
                        @if(isset($data['expenses_by_type']) && count($data['expenses_by_type']) > 0)
                            <div class="space-y-3">
                                @foreach($data['expenses_by_type'] as $type)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">{{ $type->type }}</span>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($type->total_amount / collect($data['expenses_by_type'])->max('total_amount')) * 100 }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">${{ number_format($type->total_amount, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No expense data available</p>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-8 bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">⚡ Quick Actions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('expense.v1.new') }}" class="flex items-center justify-center p-4 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition">
                            <div class="text-center">
                                <div class="text-2xl mb-2">➕</div>
                                <p class="text-sm font-medium text-gray-700">Add Expense</p>
                            </div>
                        </a>
                        <a href="{{ route('expense_type.v1.index') }}" class="flex items-center justify-center p-4 bg-white border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition">
                            <div class="text-center">
                                <div class="text-2xl mb-2">🏷️</div>
                                <p class="text-sm font-medium text-gray-700">Manage Types</p>
                            </div>
                        </a>
                        <a href="{{ route('expense.v1.index') }}" class="flex items-center justify-center p-4 bg-white border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition">
                            <div class="text-center">
                                <div class="text-2xl mb-2">📊</div>
                                <p class="text-sm font-medium text-gray-700">View Expenses</p>
                            </div>
                        </a>
                        <a href="{{ route('expense.audit.logs') }}" class="flex items-center justify-center p-4 bg-white border border-gray-200 rounded-lg hover:bg-yellow-50 hover:border-yellow-300 transition">
                            <div class="text-center">
                                <div class="text-2xl mb-2">📋</div>
                                <p class="text-sm font-medium text-gray-700">Audit Logs</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>