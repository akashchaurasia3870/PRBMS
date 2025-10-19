<x-app-layout>
    <div class="py-2">
        <div class="mx-auto px-2">
            <div class="bg-white shadow-xl rounded-lg overflow-y-auto">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8">
                    <div class="flex items-center">
                        <div class="text-4xl text-white mr-4">🏷️</div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white">Create Expense Type</h1>
                            <p class="text-blue-100 mt-1">Add a new category for organizing expenses</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="p-6 sm:p-0">
                    <form method="POST" action="{{ route('expense_type.v2.new') }}" class="space-y-6">
                        @csrf
                        @include('modules.expense_type.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>