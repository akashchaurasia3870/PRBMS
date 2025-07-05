<x-app-layout>
    <div class="mx-auto bg-white rounded shadow-md p-5 h-[100%] overflow-y-scroll no-scrollbar">
        <h2 class="text-2xl font-semibold mb-4">Edit Roles</h2>
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        <form id="edit-data-form" action="{{ route('dashboard_update.roles') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="id" value="{{ old('id', $data->id) }}">
            
            <div class="mb-4">
                <label for="role_name" class="block text-gray-700 text-sm font-bold mb-2">Role Name:</label>
                <input type="text" name="role_name" id="role_name" value="{{ old('role_name', $data->role_name) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('role_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="role_desc" class="block text-gray-700 text-sm font-bold mb-2">Role Description:</label>
                <textarea name="role_desc" id="role_desc" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('role_desc', $data->role_desc) }}</textarea>
                @error('role_desc')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="role_lvl" class="block text-gray-700 text-sm font-bold mb-2">Role Level:</label>
                <input type="number" name="role_lvl" id="role_lvl" value="{{ old('role_lvl', $data->role_lvl) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('role_lvl')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-400 hover:bg-blue-700 text-dark font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
