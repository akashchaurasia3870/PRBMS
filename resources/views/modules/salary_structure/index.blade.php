<x-app-layout>
    <div class="">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">💼 Salary Structure Management</h1>
                            <p class="text-gray-600 mt-1">Manage employee salary structures and compensation</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('dashboard_salary.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                ➕ Create Structure
                            </a>
                            <a href="{{ route('dashboard_salary.dashboard') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-200">
                                📊 Dashboard
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            ✅ {{ session('success') }}
                        </div>
                    @endif

                    <!-- Enhanced Filters -->
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-6 rounded-xl mb-6 border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                🔍 Search & Filters
                            </h3>
                        </div>
                        
                        <form method="GET" action="{{ route('dashboard_salary.index') }}">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-2">🔍 Search</label>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by employee name..." class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-2">💰 Min Salary</label>
                                    <input type="number" name="salary_min" value="{{ request('salary_min') }}" placeholder="Minimum salary" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-2">💰 Max Salary</label>
                                    <input type="number" name="salary_max" value="{{ request('salary_max') }}" placeholder="Maximum salary" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div class="flex items-end">
                                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 shadow-sm">
                                        🔍 Search
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap gap-3 items-center">
                                <a href="{{ route('dashboard_salary.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition duration-200 border border-gray-300">
                                    ❌ Clear All
                                </a>
                                <div class="text-xs text-gray-500 ml-auto">
                                    {{ $structures->total() }} results found
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Basic Salary</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allowances</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gross Salary</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($structures as $structure)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $structure->user->name ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">ID: {{ $structure->user_id }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">₹{{ number_format($structure->basic_salary, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                HRA: ₹{{ number_format($structure->hra, 0) }}
                                                <span class="text-xs text-gray-500">({{ $structure->hra_percentage }}%)</span>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                DA: ₹{{ number_format($structure->da, 0) }} • Other: ₹{{ number_format($structure->other_allowance, 0) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">₹{{ number_format($structure->gross_salary, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('dashboard_salary.edit', $structure->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">✏️</a>
                                                <form action="{{ route('dashboard_salary.delete', $structure->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">🗑️</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="text-gray-500">
                                                <div class="text-6xl mb-4">💼</div>
                                                <h3 class="text-lg font-medium mb-2">No salary structures found</h3>
                                                <p class="text-sm">Create salary structures to get started.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="lg:hidden space-y-4">
                        @forelse($structures as $structure)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $structure->user->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">Basic: ₹{{ number_format($structure->basic_salary, 0) }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-gray-900">₹{{ number_format($structure->gross_salary, 0) }}</div>
                                        <div class="text-xs text-gray-500">Gross</div>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600 mb-3">
                                    HRA: ₹{{ number_format($structure->hra, 0) }} • DA: ₹{{ number_format($structure->da, 0) }} • Other: ₹{{ number_format($structure->other_allowance, 0) }}
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('dashboard_salary.edit', $structure->id) }}" class="text-indigo-600">✏️</a>
                                    <form action="{{ route('dashboard_salary.delete', $structure->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                        @csrf
                                        <button type="submit" class="text-red-600">🗑️</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="text-gray-500">
                                    <div class="text-6xl mb-4">💼</div>
                                    <h3 class="text-lg font-medium mb-2">No salary structures found</h3>
                                    <p class="text-sm mb-4">Create salary structures to get started.</p>
                                    <a href="{{ route('dashboard_salary.create') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                        Create Structure
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($structures->hasPages())
                        <div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                            <div class="text-sm text-gray-700">
                                Showing {{ $structures->firstItem() }} to {{ $structures->lastItem() }} of {{ $structures->total() }} results
                            </div>
                            <div class="flex justify-center">
                                {{ $structures->appends(request()->query())->links('pagination::tailwind') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
