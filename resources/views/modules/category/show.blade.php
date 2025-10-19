<x-app-layout>
    <div class="mx-auto bg-white rounded-2xl shadow-lg p-6 h-full overflow-y-auto no-scrollbar">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">📁 Category Details</h2>
        <div class="mb-4 flex flex-col gap-2">
            <div><span class="font-semibold text-gray-700">Name:</span> {{ $category->name }}</div>
            <div><span class="font-semibold text-gray-700">Code:</span> {{ $category->code }}</div>
            <div><span class="font-semibold text-gray-700">Description:</span> {{ $category->description ?? '-' }}</div>
        </div>
        <div class="flex gap-2 mt-6">
            <a href="{{ route('category.edit', $category->id) }}" class="flex items-center space-x-1 text-blue-600 hover:bg-blue-50 border border-blue-100 rounded-lg px-4 py-2 transition font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5h7M11 12h7m-7 7h7M5 5h.01M5 12h.01M5 19h.01" /></svg>
                <span>Edit</span>
            </a>
            <a href="{{ route('category.index') }}" class="flex items-center space-x-1 text-gray-700 bg-gray-200 hover:bg-gray-300 border border-gray-100 rounded-lg px-4 py-2 transition font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                <span>Back to List</span>
            </a>
        </div>
    </div>
</x-app-layout>
