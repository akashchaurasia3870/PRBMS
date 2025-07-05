<x-app-layout>
    <div class="mx-auto bg-white rounded-2xl shadow-lg p-6 h-full overflow-y-auto no-scrollbar">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold text-gray-800">📝 Apply Leave</h2>
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-2 rounded m-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-2 rounded m-4">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <div class="max-w-xl mx-auto">
            <form action="{{ route('dashboard_leave.create_leave_request') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Username Select -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <select name="user_id" id="user_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" required>
                        @if(count($users) === 1)
                            <option value="{{ $users[0]->id }}" selected>{{ $users[0]->name }}</option>
                        @else
                            <option value="" disabled selected>Select user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Leave Type -->
                <div>
                    <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-1">Leave Type</label>
                    <select name="leave_type" id="leave_type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" required>
                        <option value="" disabled selected>Select leave type</option>
                        <option value="sick">Sick</option>
                        <option value="casual">Casual</option>
                        <option value="unpaid">Unpaid</option>
                        <option value="earned">Earned</option>
                    </select>
                </div>

                <!-- Reason -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                    <input type="text" name="reason" id="reason" maxlength="255" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" required>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" maxlength="255" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" required></textarea>
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">From (Start Date)</label>
                    <input type="date" name="start_date" id="start_date" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" required>
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">To (End Date)</label>
                    <input type="date" name="end_date" id="end_date" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" required>
                </div>

                <div>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition">Apply Leave</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
