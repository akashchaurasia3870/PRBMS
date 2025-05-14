<div class="p-6 bg-white rounded-lg shadow-md max-w-2xl mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">User Roles</h2>
    
    @if(!isset($data) || empty($data) || (is_countable($data) && count($data) === 0))
        <div class="text-center text-gray-400 py-8">No Data Available</div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role Level</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Created At</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($data as $role)
                        <tr>
                            <td class="px-4 py-3 text-gray-700">{{ $role->role_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $role->role_lvl ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ $role->created_at ? $role->created_at->format('Y-m-d') : 'N/A' }}
                            </td>
                            <td class="px-4 py-3">
                                <form action="{{ route('dashboard_remove_users_roles.roles', $role->id ?? 0) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this role?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white text-xs font-semibold rounded hover:bg-red-600 transition">
                                        Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-gray-400">No roles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>