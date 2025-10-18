<x-app-layout>
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Expense Type Details</h1>
    <div class="bg-white p-6 rounded shadow space-y-2">
        <p><strong>Type:        </strong> {{ $data->type }}</p>
        <p><strong>Description: </strong> {{ $data->description }}</p>
        <p><strong>Amount:      </strong> {{ $data->amount ?? '' }}</p>
        <p><strong>Date:        </strong> {{ $data->expense_date ?? '' }}</p>
    </div>
</div>
</x-app-layout>