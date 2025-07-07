<x-app-layout>
    <div class="mx-auto bg-white rounded-2xl shadow-lg p-6 h-full overflow-y-auto no-scrollbar">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">💰 Salary Structures</h2>

        @if(session('success'))
            <div class="mb-4 p-4 rounded bg-green-100 text-green-800 border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-y-auto no-scrollbar flex flex-col justify-between min-h-[75vh]">
            <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden" id="salary-structures-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">User</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Basic Salary</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">HRA</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">DA</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Other</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody id="salary-structures-tbody">
                    @forelse ($structures as $structure)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="px-5 py-4 text-gray-800 font-medium">{{ $structure->user->name }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $structure->basic_salary }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $structure->hra }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $structure->da }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $structure->other_allowance }}</td>
                            <td class="px-5 py-4 flex space-x-2">
                                <a href="{{ route('dashboard_salary.edit', $structure->id) }}"
                                   class="flex items-center space-x-1 text-blue-600 hover:bg-blue-50 border border-blue-100 rounded-lg px-3 py-1 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M11 5h7M11 12h7m-7 7h7M5 5h.01M5 12h.01M5 19h.01" />
                                    </svg>
                                    <span>Edit</span>
                                </a>
                                <form action="{{ route('dashboard_salary.delete', $structure->id) }}" method="POST"
                                      onsubmit="return confirm('Delete this record?')">
                                    @csrf
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
                                {{-- If you want a "View" button, uncomment below and add a route --}}
                                {{-- 
                                <a href="{{ route('dashboard_salary.show', $structure->id) }}"
                                   class="flex items-center space-x-1 text-blue-600 hover:bg-blue-50 border border-blue-100 rounded-lg px-3 py-1 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M11 5h7M11 12h7m-7 7h7M5 5h.01M5 12h.01M5 19h.01" />
                                    </svg>
                                    <span>View</span>
                                </a>
                                --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-500">No salary structures found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6">
                {{ $structures->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
