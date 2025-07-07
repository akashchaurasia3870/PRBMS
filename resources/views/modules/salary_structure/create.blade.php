<x-app-layout>
    <div class="mx-auto bg-white rounded shadow-md p-5 w-[100%] h-[100%] overflow-y-scroll">
        <h2 class="text-2xl font-semibold mb-4">Add Salary Structure</h2>
        <form action="{{ route('dashboard_salary.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">User:</label>
                <select name="user_id" id="user_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="basic_salary" class="block text-gray-700 text-sm font-bold mb-2">Basic Salary:</label>
                <input type="number" name="basic_salary" id="basic_salary" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="hra" class="block text-gray-700 text-sm font-bold mb-2">HRA:</label>
                <input type="number" name="hra" id="hra" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="da" class="block text-gray-700 text-sm font-bold mb-2">DA:</label>
                <input type="number" name="da" id="da" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="other_allowance" class="block text-gray-700 text-sm font-bold mb-2">Other Allowance:</label>
                <input type="number" name="other_allowance" id="other_allowance" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-dark font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Save
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
