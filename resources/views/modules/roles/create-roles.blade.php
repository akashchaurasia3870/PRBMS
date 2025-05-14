<x-app-layout>
    <div class="mx-auto bg-white rounded shadow-md p-5 w-[100%] h-[100%] overflow-y-scroll">
        <h2 class="text-2xl font-semibold mb-4">Create Roles</h2>
        <form action="{{ route('dashboard_store.roles') }}" method="POST">
            @csrf
            @method('POST')
            
            <div class="mb-4">
                <label for="role_name" class="block text-gray-700 text-sm font-bold mb-2">Role Name:</label>
                <input type="text" name="role_name" id="role_name" value="{{ old('role_name', $data['role_name'] ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label for="role_desc" class="block text-gray-700 text-sm font-bold mb-2">Role Description:</label>
                <textarea name="role_desc" id="role_desc" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('role_desc', $data['role_desc'] ?? '') }}</textarea>
            </div>
            
            <div class="mb-4">
                <label for="role_lvl" class="block text-gray-700 text-sm font-bold mb-2">Role Level:</label>
                <input type="number" name="role_lvl" id="role_lvl" value="{{ old('role_lvl', $data['role_lvl'] ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-dark font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Create
                </button>
            </div>
        </form>
    </div>
</x-app-layout>