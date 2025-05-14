<x-app-layout>
    <div class="mx-auto bg-white rounded-2xl shadow-lg p-6 h-full overflow-y-auto no-scrollbar">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">👥 Users</h2>

        <div class="overflow-y-auto no-scrollbar flex flex-col justify-between min-h-[75vh]">
            <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden" id="users-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Name</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Email</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Created At</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody id="users-tbody">
                    @forelse ($users as $user)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="px-5 py-4 text-gray-800 font-medium">{{ $user->name }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $user->email }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $user->created_at->format('Y-m-d') }}</td>
                            <td class="px-5 py-4 flex space-x-2">

                                <a href={{ route('dashboard_edit.user',$user->id) }} 
                                   class="flex items-center space-x-1 text-blue-600 hover:bg-blue-50 border border-blue-100 rounded-lg px-3 py-1 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M11 5h7M11 12h7m-7 7h7M5 5h.01M5 12h.01M5 19h.01" />
                                    </svg>
                                    <span>Edit</span>
                                </a>
                                
                                <form action={{ route('dashboard_destroy.user') }} method="POST" 
                                onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $user->id }}"/>
                                <button type="submit"
                                class="flex items-center space-x-1 text-red-600 hover:bg-red-50 border border-red-100 rounded-lg px-3 py-1 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span>Delete</span>
                                    </button>
                                </form>
                                <a href={{ route('dashboard_details.user',$user->id) }} 
                                class="flex items-center space-x-1 text-blue-600 hover:bg-blue-50 border border-blue-100 rounded-lg px-3 py-1 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5h7M11 12h7m-7 7h7M5 5h.01M5 12h.01M5 19h.01" />
                                    </svg>
                                    <span>View</span>
                                </a>
                                
                                
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6">
                {{ $users->links() }}
            </div>

        </div>
    </div>
</x-app-layout>

