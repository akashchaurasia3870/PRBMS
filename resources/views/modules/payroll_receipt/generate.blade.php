{{-- <x-app-layout>
<div class="max-w-lg mx-auto p-8 bg-white rounded-lg shadow-lg">

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    @if (session('status') === 'warning' && session('message'))
        <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
            {{ session('message') }}
        </div>
    @endif
    
    <h2 class="text-3xl font-extrabold mb-6 text-center text-blue-800">Generate Payroll Receipt</h2>

    <form action="{{ route('dashboard_payroll.generatePayroll') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label class="block font-semibold mb-2">Month</label>
            <input type="number" name="month" min="1" max="12" required
                class="w-full border border-blue-300 p-2 rounded focus:ring focus:ring-blue-200"
                placeholder="Enter month (1-12)">
        </div>

        <div>
            <label class="block font-semibold mb-2">Year</label>
            <input type="number" name="year" min="2023" max="2050" required
                class="w-full border border-blue-300 p-2 rounded focus:ring focus:ring-blue-200"
                placeholder="Enter year (e.g. 2025)">
        </div>

        <div>
            <label class="block font-semibold mb-2">Generate For</label>
            <div class="flex items-center space-x-3">
                <input type="checkbox" id="all_users" name="all_users" value="1" class="h-5 w-5 text-blue-600 border-gray-300 rounded">
                <label for="all_users" class="text-gray-700">All Users</label>
            </div>
            <div class="mt-3">
                <select name="user_id" id="user_id" class="w-full border border-blue-300 p-2 rounded focus:ring focus:ring-blue-200" {{ old('all_users') ? 'disabled' : '' }}>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            <small class="text-gray-500">Check "All Users" to generate for everyone, or select a user.</small>
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">Generate Payroll</button>
            <a href="{{ route('dashboard_payroll.index') }}"
                class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const allUsersCheckbox = document.getElementById('all_users');
        const userSelect = document.getElementById('user_id');

        function toggleUserSelect() {
            userSelect.disabled = allUsersCheckbox.checked;
        }

        allUsersCheckbox.addEventListener('change', toggleUserSelect);
        toggleUserSelect();
    });
</script>
@endpush

</x-app-layout> --}}


<x-app-layout>
<div class="mx-auto p-8 bg-white rounded-2xl shadow-lg">

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-300 text-red-800 rounded-lg">
            <h3 class="font-semibold mb-2 flex items-center"><svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728"></path></svg> Validation Error</h3>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Warning Message --}}
    @if (session('status') === 'warning' && session('message'))
        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-300 text-yellow-800 rounded-lg">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 18h.01"></path>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        </div>
    @endif

    <h2 class="text-3xl font-extrabold mb-8 text-center text-blue-800">📄 Generate Payroll Receipt</h2>

    <form action="{{ route('dashboard_payroll.generatePayroll') }}" method="POST" class="space-y-6 p-4">
        @csrf

        <div>
            <label class="block font-semibold text-gray-700 mb-2">Month</label>
            {{-- <input type="number" name="month" min="1" max="12" required
                class="w-full border border-gray-300 p-3 rounded-lg focus:ring focus:ring-blue-200"
                placeholder="Enter month (1-12)"> --}}
            <select name="month"
                class="border-gray-300 p-2 rounded-lg w-32 focus:ring focus:ring-blue-100"
                id="monthFilter">
            @php
                $selectedMonth = request('month') ?? now()->month;
            @endphp
            @foreach ([
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
            ] as $num => $monthName)
                <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>
                    {{ $monthName }}
                </option>
            @endforeach
        </select>
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-2">Year</label>
            {{-- <input type="number" name="year" min="2023" max="2050" required
                class="w-full border border-gray-300 p-3 rounded-lg focus:ring focus:ring-blue-200"
                placeholder="Enter year (e.g. 2025)"> --}}
            <input type="number" name="year" placeholder="Year" value="{{ request('year', 2025) }}"
               class="border-gray-300 p-2 rounded-lg w-[10em] focus:ring focus:ring-blue-100"
               min="2025" max="2025" id="yearFilter">
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-2">Generate For</label>
            <div class="flex items-center space-x-3">
                <input type="checkbox" id="all_users" name="all_users" value="1"
                    class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring focus:ring-blue-200">
                <label for="all_users" class="text-gray-700 font-medium">All Users</label>
            </div>

            <div class="mt-3">
                <select name="user_id" id="user_id"
                    class="w-1/4 border border-gray-300 p-3 rounded-lg focus:ring focus:ring-blue-200"
                    {{ old('all_users') ? 'disabled' : '' }}>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <p class="text-sm text-gray-500 mt-2">Check "All Users" to generate for everyone, or select a specific user.</p>
        </div>

        <div class="flex items-center justify-between pt-4">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition shadow-sm">
                📤 Generate Payroll
            </button>

            <a href="{{ route('dashboard_payroll.index') }}"
                class="text-gray-600 font-medium hover:text-blue-600 transition underline">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const allUsersCheckbox = document.getElementById('all_users');
    const userSelect = document.getElementById('user_id');

    function toggleUserSelect() {
        userSelect.disabled = allUsersCheckbox.checked;
    }

    allUsersCheckbox.addEventListener('change', toggleUserSelect);
    toggleUserSelect();
});
</script>
@endpush

</x-app-layout>
