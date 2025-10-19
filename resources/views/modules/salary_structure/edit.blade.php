<x-app-layout>
    <div class="mx-auto bg-white rounded shadow-md p-5 h-[100%] overflow-y-scroll no-scrollbar">
        <h2 class="text-2xl font-semibold mb-4">Edit Salary Structure</h2>
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        <form id="edit-salary-structure-form" action="{{ route('dashboard_salary.update', $salary_structure->id ?? '#') }}" method="POST">
            @csrf
            @method('POST')
            <input type="hidden" name="id" id="id" value="{{ old('id', $salary_structure->id ?? '') }}">
            <div class="mb-4">
                <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">User:</label>
                <input type="text" name="user_name" id="user_name" value="{{ $user->name ?? 'N/A' }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-50" disabled>
            </div>
            <div class="mb-4">
                <label for="basic_salary" class="block text-gray-700 text-sm font-bold mb-2">Basic Salary:</label>
                <input type="number" name="basic_salary" id="basic_salary" value="{{ old('basic_salary', $salary_structure->basic_salary ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                @error('basic_salary')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="hra" class="block text-gray-700 text-sm font-bold mb-2">HRA:</label>
                <input type="number" name="hra" id="hra" value="{{ old('hra', $salary_structure->hra ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('hra')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="da" class="block text-gray-700 text-sm font-bold mb-2">DA:</label>
                <input type="number" name="da" id="da" value="{{ old('da', $salary_structure->da ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('da')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="other_allowance" class="block text-gray-700 text-sm font-bold mb-2">Other Allowance:</label>
                <input type="number" name="other_allowance" id="other_allowance" value="{{ old('other_allowance', $salary_structure->other_allowance ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('other_allowance')
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
