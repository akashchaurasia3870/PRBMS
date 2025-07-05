<x-app-layout>
    <div class="flex justify-center">
        <div class="relative bg-white rounded-2xl shadow-lg p-6 h-full overflow-y-auto no-scrollbar max-w-2xl w-full">
            <h2 class="text-3xl font-extrabold text-gray-800 mb-6">📝 Leave Details</h2>

            <form id="leave-details-form" 
                  action="{{ route('dashboard_leave.edit_leave_info', request()->route('id')) }}" 
                  method="POST" 
                  class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $data->id }}">

                <div>
                    <label class="block text-gray-700 font-semibold mb-1">User Name</label>
                    <input type="text" name="user_name" value="{{ $data->name }}" 
                           class="border rounded px-3 py-2 w-full bg-gray-100" 
                           disabled>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1">User ID</label>
                    <input type="text" name="user_id" value="{{ $data->user_id }}" 
                           class="border rounded px-3 py-2 w-full bg-gray-100" 
                           disabled>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Leave Type</label>
                        <select name="leave_type" class="border rounded px-3 py-2 w-full" disabled>
                            @foreach(['sick', 'casual', 'earned', 'unpaid'] as $type)
                                <option value="{{ $type }}" {{ $data->leave_type == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1">From Date</label>
                    <input type="date" name="start_date" value="{{ \Carbon\Carbon::parse($data->start_date)->format('Y-m-d') }}" 
                           class="border rounded px-3 py-2 w-full bg-gray-100" 
                           disabled>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1">To Date</label>
                    <input type="date" name="end_date" value="{{ \Carbon\Carbon::parse($data->end_date)->format('Y-m-d') }}" 
                           class="border rounded px-3 py-2 w-full bg-gray-100" 
                           disabled>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Reason</label>
                    <textarea name="reason" rows="3" 
                              class="border rounded px-3 py-2 w-full bg-gray-100" 
                              disabled>{{ $data->reason }}</textarea>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Description</label>
                    <textarea name="description" rows="3" 
                              class="border rounded px-3 py-2 w-full bg-gray-100" 
                              disabled>{{ $data->description ?? '' }}</textarea>
                </div>

                <div class="flex space-x-2 mt-6">
                    <button type="button" id="edit-btn" 
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                        Edit
                    </button>
                    <button type="button" id="cancel-btn" 
                            class="bg-gray-300 text-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition hidden">
                        Cancel
                    </button>
                    <button type="submit" id="save-btn" 
                            class="bg-green-300 text-gray-300 px-4 py-2 rounded hover:bg-green-400 transition hidden">
                        Save
                    </button>
                </div>
            </form>
        </div>

        {{-- Pagination on right side, sticky --}}
        <div class="sticky top-24 self-start ml-8 flex flex-col items-end">
            {{-- Example pagination, replace with your pagination links --}}
            @if(isset($pagination) && $pagination)
                <div class="bg-white rounded-lg shadow p-4">
                    {!! $pagination->links() !!}
                </div>
            @else
                {{-- Example static pagination --}}
                <div class="bg-white rounded-lg shadow p-4">
                    <nav class="inline-flex -space-x-px">
                        <a href="#" class="px-3 py-2 rounded-l border border-gray-300 bg-gray-50 text-gray-500 hover:bg-gray-100">Prev</a>
                        <a href="#" class="px-3 py-2 border-t border-b border-gray-300 bg-white text-blue-600">1</a>
                        <a href="#" class="px-3 py-2 border-t border-b border-gray-300 bg-white text-gray-700 hover:bg-gray-100">2</a>
                        <a href="#" class="px-3 py-2 border-t border-b border-gray-300 bg-white text-gray-700 hover:bg-gray-100">3</a>
                        <a href="#" class="px-3 py-2 rounded-r border border-gray-300 bg-gray-50 text-gray-500 hover:bg-gray-100">Next</a>
                    </nav>
                </div>
            @endif
        </div>
    </div>

    <script>
        const editBtn = document.getElementById('edit-btn');
        const cancelBtn = document.getElementById('cancel-btn');
        const saveBtn = document.getElementById('save-btn');
        const form = document.getElementById('leave-details-form');
        const inputs = form.querySelectorAll('input, textarea, select');

        // Store original values for cancel
        let originalValues = {};
        inputs.forEach(input => {
            originalValues[input.name] = input.value;
        });

        editBtn.addEventListener('click', function() {
            inputs.forEach(input => {
                if (input.name !== 'user_name' && input.name !== 'user_id') {
                    input.removeAttribute('disabled');
                    input.classList.remove('bg-gray-100');
                }
            });
            editBtn.classList.add('hidden');
            cancelBtn.classList.remove('hidden');
            saveBtn.classList.remove('hidden');
        });

        cancelBtn.addEventListener('click', function() {
            // Reload the page to reset the form and UI state
            window.location.reload();
        });

        form.addEventListener('submit', function() {
            // Optionally, disable buttons to prevent double submit
            saveBtn.disabled = true;
        });
    </script>
</x-app-layout>
