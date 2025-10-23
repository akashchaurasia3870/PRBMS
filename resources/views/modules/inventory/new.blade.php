<x-app-layout>
    <div class="py-2">
        <div class="mx-auto px-2">
            <div class="bg-white shadow-xl rounded-lg overflow-y-auto">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-8">
                    <div class="flex items-center mb-6">
                        <div class="text-4xl text-white mr-4">📦</div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white">Add New Inventory Item</h1>
                            <p class="text-green-100 mt-1">Create a new item for your inventory management</p>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="space-y-2">
                        <div class="w-full bg-green-800 bg-opacity-30 rounded-full h-3">
                            <div id="progressBar" class="bg-white h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                        <p id="progressText" class="text-green-100 text-sm">Step 0 of 3: Ready to start</p>
                    </div>
                </div>

                <!-- Form -->
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('inventory.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @include('modules.inventory.form', ['mode' => 'create', 'categories' => $data])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>