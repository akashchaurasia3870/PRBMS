<x-app-layout>
    <x-content class="flex-1 overflow-y-auto p-4">
        <div class="max-w-md mx-auto bg-white rounded shadow-md p-5">
            <h2 class="text-2xl font-semibold mb-4">User Details</h2>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                <p class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">{{ $user->name }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <p class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">{{ $user->email }}</p>
            </div>
        </div>
    </x-content>
</x-app-layout>
