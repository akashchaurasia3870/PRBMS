<x-app-layout>
    <div class="">
        <div class="mx-auto">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8">
                    <div class="flex items-center mb-6">
                        <div class="text-4xl text-white mr-4">💰</div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white">Add New Expense</h1>
                            <p class="text-blue-100 mt-1">Record a new expense for your organization</p>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="space-y-2">
                        <div class="w-full bg-blue-800 bg-opacity-30 rounded-full h-3">
                            <div id="progressBar" class="bg-white h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                        <p id="progressText" class="text-blue-100 text-sm">Step 0 of 5: Ready to start</p>
                    </div>
                </div>

                <!-- Form -->
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('expense.v2.new') }}" class="space-y-6">
                        @csrf
                        @include('modules.expense_tracker.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>