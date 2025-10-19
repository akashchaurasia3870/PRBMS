<x-app-layout>
    <div class="mx-auto bg-white rounded-2xl shadow-lg p-6 h-full overflow-y-auto no-scrollbar">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">📂 Category List</h2>
        <a href="{{ route('category.create') }}" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 transition font-semibold">Add Category</a>
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex items-center" role="alert">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span>{{ session('success') }}</span>
                <button type="button" class="absolute top-2 right-2 text-green-700" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border border-gray-200 rounded-xl overflow-hidden" id="category-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Name</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Code</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Description</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($categories as $cat)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="px-5 py-4 text-gray-800 font-medium">{{ $cat->name }}</td>
                        <td class="px-5 py-4 text-gray-700">{{ $cat->code }}</td>
                        <td class="px-5 py-4 text-gray-700">{{ Str::limit($cat->description, 50) }}</td>
                        <td class="px-5 py-4 flex space-x-2">
                            <a href="{{ route('category.show', $cat->id) }}" class="flex items-center space-x-1 text-blue-600 hover:bg-blue-50 border border-blue-100 rounded-lg px-3 py-1 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5h7M11 12h7m-7 7h7M5 5h.01M5 12h.01M5 19h.01" /></svg>
                                <span>View</span>
                            </a>
                            <a href="{{ route('category.edit', $cat->id) }}" class="flex items-center space-x-1 text-yellow-600 hover:bg-yellow-50 border border-yellow-100 rounded-lg px-3 py-1 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5h7M11 12h7m-7 7h7M5 5h.01M5 12h.01M5 19h.01" /></svg>
                                <span>Edit</span>
                            </a>
                            <form action="{{ route('category.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete this category?');" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="flex items-center space-x-1 text-red-600 hover:bg-red-50 border border-red-100 rounded-lg px-3 py-1 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    <span>Delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-6 text-gray-500">No categories found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    </div>
</x-app-layout>
