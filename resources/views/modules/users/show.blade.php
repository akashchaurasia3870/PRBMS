<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <!-- Header -->
                    <div class="flex justify-between items-start mb-8">
                        <div class="flex items-center">
                            <div class="text-4xl mr-4">👤</div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
                                <p class="text-gray-600 mt-1">User ID: #{{ $data->id }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('dashboard_edit.user', $data->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                ✏️ Edit
                            </a>
                            <a href="{{ route('dashboard_list.user') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                ← Back to List
                            </a>
                        </div>
                    </div>

                    <!-- User Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Main Details -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">User Information</h2>
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <img class="h-16 w-16 rounded-full" src="{{ $data->profile_photo_url }}" alt="{{ $data->name }}">
                                        <div class="ml-4">
                                            <div class="text-xl font-bold text-gray-900">{{ $data->name }}</div>
                                            <div class="text-gray-600">{{ $data->email }}</div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Status</label>
                                        <div class="mt-1">
                                            @if($data->deleted)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                    Inactive
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Email Verified</label>
                                        <div class="mt-1">
                                            @if($data->email_verified_at)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    ✅ Verified
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                    ⏳ Pending
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Details -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Account Details</h2>
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
                                    @if($data->email_verified_at)
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Email Verified:</span>
                                        <span class="text-gray-900">{{ \Carbon\Carbon::parse($data->email_verified_at)->format('M d, Y g:i A') }}</span>
                                    </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Two Factor:</span>
                                        <span class="text-gray-900">
                                            @if($data->two_factor_secret)
                                                <span class="text-green-600">Enabled</span>
                                            @else
                                                <span class="text-gray-400">Disabled</span>
                                            @endif
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
                                Need to make changes? You can edit this user or manage their access permissions.
                            </div>
                            <div class="flex space-x-3">
                                @if(!$data->deleted)
                                <form action="{{ route('dashboard_destroy.user') }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                        🗑️ Delete User
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>