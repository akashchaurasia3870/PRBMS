<div class="w-full mx-auto bg-white shadow-lg rounded-lg p-6 mt-6">
    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Address Details</h2>
    @php
        // Get the last segment of the URL as user_id
        $user_id = request()->segment(count(request()->segments()));
    @endphp
    <!-- Display Mode -->
    <div id="addressDisplay" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
            <span class="block text-gray-500 text-sm">Country</span>
            <span class="font-medium text-gray-900">{{ $data->country ?? 'N/A' }}</span>
        </div>
        <div>
            <span class="block text-gray-500 text-sm">State</span>
            <span class="font-medium text-gray-900">{{ $data->state ?? 'N/A' }}</span>
        </div>
        <div>
            <span class="block text-gray-500 text-sm">City</span>
            <span class="font-medium text-gray-900">{{ $data->city ?? 'N/A' }}</span>
        </div>
        <div>
            <span class="block text-gray-500 text-sm">Area</span>
            <span class="font-medium text-gray-900">{{ $data->area ?? 'N/A' }}</span>
        </div>
        <div>
            <span class="block text-gray-500 text-sm">Locality</span>
            <span class="font-medium text-gray-900">{{ $data->locality ?? 'N/A' }}</span>
        </div>
        <div>
            <span class="block text-gray-500 text-sm">Landmark</span>
            <span class="font-medium text-gray-900">{{ $data->landmark ?? 'N/A' }}</span>
        </div>
        <div>
            <span class="block text-gray-500 text-sm">Street</span>
            <span class="font-medium text-gray-900">{{ $data->street ?? 'N/A' }}</span>
        </div>
        <div>
            <span class="block text-gray-500 text-sm">House No</span>
            <span class="font-medium text-gray-900">{{ $data->house_no ?? 'N/A' }}</span>
        </div>
        <div>
            <span class="block text-gray-500 text-sm">Pincode</span>
            <span class="font-medium text-gray-900">{{ $data->pincode ?? 'N/A' }}</span>
        </div>
        <div>
            <span class="block text-gray-500 text-sm">Contact No</span>
            <span class="font-medium text-gray-900">{{ $data->contact_no ?? 'N/A' }}</span>
        </div>
        <div>
            <span class="block text-gray-500 text-sm">Emergency Contact No</span>
            <span class="font-medium text-gray-900">{{ $data->emergency_contact_no ?? 'N/A' }}</span>
        </div>
    </div>

    <!-- Edit Button -->
    <button id="edit_button"
        onclick="document.getElementById('addressForm').classList.remove('hidden'); document.getElementById('addressDisplay').classList.add('hidden'); this.classList.add('hidden')"
        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
    >Edit Address</button>

    <!-- Edit Form -->
    <form 
        id="addressForm" 
        action="{{ route('dashboard_contact_update.user') }}" 
        method="POST" 
        class="hidden mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
    >
        @csrf
        @method('POST')

        <input type="hidden" name="user_id" value="{{ $user_id ?? '' }}"/>

        @php
            $fields = ['country', 'state', 'city', 'area', 'locality', 'landmark', 'street', 'house_no', 'pincode', 'contact_no', 'emergency_contact_no'];
        @endphp

        @foreach ($fields as $field)
            <div>
                <label class="block text-gray-500 text-sm capitalize">{{ str_replace('_', ' ', $field) }}</label>
                <input 
                    type="text" 
                    name="{{ $field }}" 
                    value="{{ $data->$field ?? '' }}" 
                    class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Enter {{ str_replace('_', ' ', $field) }}"
                />
            </div>
        @endforeach

        <div class="col-span-1 md:col-span-2 lg:col-span-3 flex gap-4 mt-4">
            <button 
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
            >Save</button>

            <button 
                type="button"
                onclick="document.getElementById('addressForm').classList.add('hidden'); document.getElementById('addressDisplay').classList.remove('hidden'); document.querySelector('#edit_button').classList.remove('hidden')"
                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition"
            >Cancel</button>
        </div>
    </form>
</div>
