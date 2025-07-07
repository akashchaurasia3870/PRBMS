<x-app-layout>

<div class="max-w-lg mx-auto p-6 bg-white rounded shadow">

    <h2 class="text-2xl font-bold mb-5">Edit Payroll Receipt</h2>

    <form action="{{ route('dashboard_payroll.update', $receipt->id) }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-semibold mb-1">Present Days</label>
            <input type="number" name="present_days" min="0" max="{{ $receipt->total_working_days }}" required
                value="{{ $receipt->present_days }}"
                class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block font-semibold mb-1">Leave Days</label>
            <input type="number" name="leave_days" min="0" max="{{ $receipt->total_working_days }}" required
                value="{{ $receipt->leave_days }}"
                class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block font-semibold mb-1">Net Salary (₹)</label>
            <input type="number" name="net_salary" step="0.01" min="0" required
                value="{{ $receipt->net_salary }}"
                class="w-full border p-2 rounded">
        </div>

        <button type="submit"
            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Update</button>
        <a href="{{ route('dashboard_payroll.index') }}"
            class="ml-2 text-gray-600 hover:underline">Cancel</a>
    </form>

</div>

</x-app-layout>
