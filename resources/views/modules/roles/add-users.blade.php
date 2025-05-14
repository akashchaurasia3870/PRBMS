<x-app-layout>
        <div class="mx-auto bg-white rounded-2xl shadow-lg p-6 h-full overflow-y-auto no-scrollbar">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-extrabold text-gray-800">👥 Add Users In Role : {{$role_name}}</h2>
                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-2 rounded m-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 text-red-700 p-2 rounded m-4">
                        {{ session('error') }}
                    </div>
                @endif

                
            </div>

            <div class="overflow-y-auto no-scrollbar flex flex-col justify-between min-h-[75vh]">
                <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden" id="users-table">
                    <thead class="bg-gray-50">
                        <tr>
                            
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                Name
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                Email
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                Created At
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody id="users-tbody">
                        @forelse ($users as $user)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">      
                                <td class="px-5 py-4 text-gray-800 font-medium">{{ $user->name }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $user->email }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $user->created_at }}</td>
                                <td class="px-5 py-4 text-center">
                                    @if ($user->role_id == $role_id && $user->role_lvl == $lvl)
                                        <form id="edit-data-form" action="{{ route('dashboard_remove_users_roles.roles') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->r_id }}" id="r-id"/>
                                            <input type="hidden" name="role_id" value="{{ $role_id }}" id="role-id"/>
                                            <input type="hidden" name="lvl" value="{{ $lvl }}" id="role-lvl"/>
                                            <input type="hidden" name="user_id" value="{{ $user->id }}" id="user-id"/>
                                            <input type="hidden" name="role_name" value="{{ $role_name }}" id="role_name"/>

                                            <button type="submit" class="inline-block align-middle ml-2 cursor-pointer">
                                                <!-- Plus SVG Icon -->
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <form id="edit-data-form" action="{{ route('dashboard_add_users_roles.roles') }}" method="POST">
                                            @csrf

                                            <input type="hidden" name="role_id" value="{{ $role_id }}" id="role-id"/>
                                            <input type="hidden" name="lvl" value="{{ $lvl }}" id="role-lvl"/>
                                            <input type="hidden" name="user_id" value="{{ $user->id }}" id="user-id"/>
                                            <input type="hidden" name="role_name" value="{{ $role_name }}" id="role_name"/>

                                            <button type="submit" class="inline-block align-middle ml-2 cursor-pointer">
                                                <!-- Plus SVG Icon -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
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

    <script>
            async function setRole(user_id){
                try {
                    const role_id = document.getElementById('role-id').value;

                    const response = await fetch("{{ route('dashboard_add_users_roles.roles') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({user_id,role_id})
                    });

                    if (response.ok) {
                        alert('Users successfully added!');
                        location.reload();
                    } else {
                        alert('Failed to add users. Please try again.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                }
            }
    </script>
</x-app-layout>
