<x-app-layout>

<div class="max-w-2xl mx-auto p-6 bg-white rounded shadow">

    <h2 class="text-2xl font-bold mb-4">Payroll Receipt Details</h2>

    <div class="space-y-3 text-gray-800">
        <p><strong>User:</strong> {{ $receipt->name }}</p>
        <p><strong>Month / Year:</strong> {{ $receipt->month }} / {{ $receipt->year }}</p>
        <p><strong>Total Working Days:</strong> {{ $receipt->total_working_days }}</p>
        <p><strong>Present Days:</strong> {{ $receipt->present_days }}</p>
        <p><strong>Leave Days:</strong> {{ $receipt->leave_days }}</p>
        <p><strong>Total Salary:</strong> ₹ {{ number_format($receipt->total_salary, 2) }}</p>
        <p><strong>Net Salary:</strong> ₹ {{ number_format($receipt->net_salary, 2) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($receipt->status) }}</p>
        <p><strong>Generated At:</strong> {{ $receipt->generated_at }}</p>
        @if($receipt->paid_at)
            <p><strong>Paid At:</strong> {{ $receipt->paid_at }}</p>
        @endif
    </div>

    <a href="{{ route('dashboard_payroll.index') }}" 
       class="inline-block mt-4 text-blue-600 hover:underline">← Back to list</a>

</div>

</x-app-layout>
