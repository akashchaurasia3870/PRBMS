<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <!-- Header -->
                    <div class="flex justify-between items-start mb-8">
                        <div class="flex items-center">
                            <div class="text-4xl mr-4">📊</div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">Expense Details</h1>
                                <p class="text-gray-600 mt-1">Expense ID: #{{ $data->id }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('expense.v1.edit', $data->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                ✏️ Edit
                            </a>
                            <a href="{{ route('expense.v1.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                ← Back to List
                            </a>
                        </div>
                    </div>

                    <!-- Expense Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Main Details -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Expense Information</h2>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Type</label>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                {{ $data->type ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Amount</label>
                                        <div class="mt-1 text-2xl font-bold text-green-600">
                                            ${{ number_format($data->amount ?? 0, 2) }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Expense Date</label>
                                        <div class="mt-1 text-lg text-gray-900">
                                            {{ $data->expense_date ? \Carbon\Carbon::parse($data->expense_date)->format('F d, Y') : 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $data->expense_date ? \Carbon\Carbon::parse($data->expense_date)->diffForHumans() : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description & Metadata -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Description</h2>
                                <div class="text-gray-700 leading-relaxed">
                                    {{ $data->description ?? 'No description provided.' }}
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Record Information</h2>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Created:</span>
                                        <span class="text-gray-900">{{ $data->created_at ? \Carbon\Carbon::parse($data->created_at)->format('M d, Y g:i A') : 'N/A' }}</span>
                                    </div>
                                    @if($data->updated_at && $data->updated_at != $data->created_at)
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Last Updated:</span>
                                        <span class="text-gray-900">{{ \Carbon\Carbon::parse($data->updated_at)->format('M d, Y g:i A') }}</span>
                                    </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Status:</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Need to make changes? You can edit this expense or delete it if it's no longer needed.
                            </div>
                            <div class="flex space-x-3">
                                <form action="{{ route('expense.v2.delete', $data->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this expense? This action cannot be undone.')">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                        🗑️ Delete Expense
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>