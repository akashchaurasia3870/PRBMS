<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-4 sm:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center mb-4 sm:mb-0">
                            <div class="text-4xl text-white mr-4">🏷️</div>
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $data->type }}</h1>
                                <p class="text-green-100 mt-1">Expense Type Details</p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                            <a href="{{ route('expense_type.v1.edit', $data->id) }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition duration-200 text-center">
                                ✏️ Edit
                            </a>
                            <a href="{{ route('expense_type.v1.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition duration-200 text-center">
                                ← Back
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Main Info -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Type Information</h2>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Category Name</label>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-lg font-medium bg-green-100 text-green-800">
                                                {{ $data->type }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Description</label>
                                        <div class="mt-1 text-gray-900 leading-relaxed">
                                            {{ $data->description ?? 'No description provided.' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Metadata -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Record Details</h2>
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

                    <!-- Actions -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                            <div class="text-sm text-gray-500">
                                This expense type can be used when creating new expense records.
                            </div>
                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                                <form action="{{ route('expense_type.v2.delete', $data->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this expense type? This action cannot be undone.')">
                                    @csrf
                                    <button type="submit" class="w-full sm:w-auto bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                        🗑️ Delete Type
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