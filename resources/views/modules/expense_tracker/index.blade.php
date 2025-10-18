<x-app-layout>
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Expense Records</h1>

    <a href="{{ route('expense.v1.new') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Expense</a>

    <table class="w-full mt-6 border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-2">Type</th>
                <th class="border p-2">Description</th>
                <th class="border p-2">Amount</th>
                <th class="border p-2">Date</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $expense)
                <tr>
                    <td class="border p-2">{{ $expense->type ?? '' }}</td>
                    <td class="border p-2">{{ $expense->description ?? '' }}</td>
                    <td class="border p-2">{{ $expense->amount ?? '' }}</td>
                    <td class="border p-2">{{ $expense->expense_date ?? '' }}</td>
                    <td class="border p-2">
                        <a href="{{ route('expense.v1.show', $expense->id) }}" class="text-blue-500">View</a> |
                        <a href="{{ route('expense.v1.edit', $expense->id) }}" class="text-yellow-500">Edit</a> |
                        <form action="{{ route('expense.v2.delete', $expense->id) }}" method="POST" class="inline">
                            @csrf @method('POST')
                            <button type="submit" class="text-red-500">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center p-4">No records found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</x-app-layout>