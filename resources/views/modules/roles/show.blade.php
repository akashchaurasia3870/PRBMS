<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <!-- Header -->
                    <div class="flex justify-between items-start mb-8">
                        <div class="flex items-center">
                            <div class="text-4xl mr-4">🛡️</div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">Role Details</h1>
                                <p class="text-gray-600 mt-1">Role ID: #{{ $data->id }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('dashboard_add_users.roles', ['id' => $data->id, 'lvl' => $data->role_lvl, 'role_name' => $data->role_name]) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                👥 Manage Users
                            </a>
                            <a href="{{ route('dashboard_edit.roles', $data->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                ✏️ Edit
                            </a>
                            <a href="{{ route('dashboard_list.roles') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                ← Back to List
                            </a>
                        </div>
                    </div>

                    <!-- Role Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Main Details -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Role Information</h2>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Role Name</label>
                                        <div class="mt-1 text-xl font-bold text-gray-900">{{ $data->role_name }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Description</label>
                                        <div class="mt-1 text-gray-700">{{ $data->role_desc }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Access Level</label>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                @if($data->role_lvl == 0) bg-gray-100 text-gray-800
                                                @elseif($data->role_lvl == 1) bg-blue-100 text-blue-800
                                                @elseif($data->role_lvl == 2) bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                Level {{ $data->role_lvl }} - 
                                                @if($data->role_lvl == 0) Basic User
                                                @elseif($data->role_lvl == 1) Standard User
                                                @elseif($data->role_lvl == 2) Supervisor
                                                @else Administrator @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Details -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">System Details</h2>
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
                                        <span class="text-gray-900">
                                            @if($data->deleted)
                                                <span class="text-red-600">Inactive</span>
                                            @else
                                                <span class="text-green-600">Active</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Permissions Overview -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Permission Level</h2>
                                <div class="space-y-2">
                                    @if($data->role_lvl >= 0)
                                        <div class="flex items-center text-sm text-green-600">
                                            <span class="mr-2">✅</span> Basic system access
                                        </div>
                                    @endif
                                    @if($data->role_lvl >= 1)
                                        <div class="flex items-center text-sm text-green-600">
                                            <span class="mr-2">✅</span> Standard user operations
                                        </div>
                                    @endif
                                    @if($data->role_lvl >= 2)
                                        <div class="flex items-center text-sm text-green-600">
                                            <span class="mr-2">✅</span> Supervisory functions
                                        </div>
                                    @endif
                                    @if($data->role_lvl >= 3)
                                        <div class="flex items-center text-sm text-green-600">
                                            <span class="mr-2">✅</span> Administrative privileges
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Need to make changes? You can edit this role or manage user assignments.
                            </div>
                            <div class="flex space-x-3">
                                @if(!$data->deleted)
                                <form action="{{ route('dashboard_destroy.roles') }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this role? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                        🗑️ Delete Role
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