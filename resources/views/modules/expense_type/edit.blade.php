<x-app-layout>
    <div class="">
        <div class="mx-auto">
            <div class="bg-white shadow-xl rounded-lg overflow-y-auto">
                <!-- Header -->
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-8">
                    <div class="flex items-center">
                        <div class="text-4xl text-white mr-4">✏️</div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white">Edit Expense Type</h1>
                            <p class="text-yellow-100 mt-1">Update "{{ $data->type }}" category details</p>
                        </div>
                    </div>
                </div>

                <!-- Alert -->
                {{-- <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="text-yellow-400 mr-3">⚠️</div>
                        <p class="text-yellow-800 text-sm">Changes will affect all expenses using this type</p>
                    </div>
                </div> --}}

                <!-- Form -->
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('expense_type.v2.edit', $data->id) }}" class="space-y-6">
                        @csrf
                        @method('POST')
                        @php $expenseType = $data; @endphp
                        @include('modules.expense_type.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>