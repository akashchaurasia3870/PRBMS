<x-app-layout>
<div class="container mx-auto px-4 py-6">
    <h1 class="text-xl font-bold mb-4">ADD Expense Type</h1>
    <form method="POST" action="{{ route('expense_type.v2.new') }}" class="bg-white p-6 rounded shadow">
        @csrf
        @include('modules.expense_type.form')
    </form>
</div>
</x-app-layout>