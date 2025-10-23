<x-app-layout>
    <div class="mx-auto bg-white rounded-2xl shadow-lg p-6 h-full overflow-y-auto no-scrollbar ">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">✏️ Edit Inventory Item</h2>
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex items-center" role="alert">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span>{{ session('success') }}</span>
                <button type="button" class="absolute top-2 right-2 text-green-700" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif
        <form action="{{ route('inventory.update', $data->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            @include('modules.inventory.form', ['mode' => 'edit', 'inventory' => $data])
            <div class="flex gap-2 mt-6">
                <button type="submit" class="flex items-center space-x-1 text-white bg-blue-600 hover:bg-blue-700 border border-blue-100 rounded-lg px-4 py-2 transition font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5h7M11 12h7m-7 7h7M5 5h.01M5 12h.01M5 19h.01" /></svg>
                    <span>Update Inventory</span>
                </button>
                <a href="{{ route('inventory.index') }}" class="flex items-center space-x-1 text-gray-700 bg-gray-200 hover:bg-gray-300 border border-gray-100 rounded-lg px-4 py-2 transition font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    <span>Cancel</span>
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
