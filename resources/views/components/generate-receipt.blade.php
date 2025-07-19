@props(['users'])

<div class="p-4 bg-white rounded-xl shadow-sm space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4 flex-wrap">

    <div class="flex flex-row justify-evenly w-full space-x-2">
        {{-- Month --}}
        <div>
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
                    <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $monthName }}</option>
                @endforeach
            </select>
        </div>

        {{-- Year --}}
        <div>
            <input type="number" name="year"
                value="{{ request('year', now()->year) }}"
                min="2020" max="2050"
                class="border-gray-300 p-2 rounded-lg w-32 focus:ring focus:ring-blue-100"
                id="yearFilter">
        </div>


        {{-- User Selection --}}
        <div class="flex-grow min-w-[180px]">
            <select name="user_id" id="user_id"
                class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-100">
                <option value="0">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Submit Button --}}
        <div class="px-2">
            <button type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-blue-700 transition">
                📤 Generate
            </button>
        </div>
    </div>

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
