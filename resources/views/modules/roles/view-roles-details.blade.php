<x-app-layout>
    <div id="role-details" class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6 mt-10">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Roles Details</h2>
        <div class="mb-6">
            <label class="block text-gray-600 text-sm font-medium mb-2">Role Name:</label>
            <p id="role-name" class="bg-gray-100 border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700">
                {{ $data['role_name'] ?? 'N/A' }}
            </p>
        </div>
        <div class="mb-6">
            <label class="block text-gray-600 text-sm font-medium mb-2">Role Description:</label>
            <p id="role-desc" class="bg-gray-100 border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700">
                {{ $data['role_desc'] ?? 'N/A' }}
            </p>
        </div>
        <div class="mb-6">
            <label class="block text-gray-600 text-sm font-medium mb-2">Role Level:</label>
            <p id="role-lvl" class="bg-gray-100 border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700">
                {{ $data['role_lvl'] ?? 'N/A' }}
            </p>
        </div>
    </div>
</x-app-layout>
