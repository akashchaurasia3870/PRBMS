<div class="mx-auto p-6 bg-white rounded-xl shadow-md mt-8">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Personal Information</h2>
    @php
        // Get the last segment of the URL as user_id
        $user_id = request()->segment(count(request()->segments()));
    @endphp
    @if(!isset($data) || empty($data) || (is_countable($data) && count($data) === 0 || count($data->documents ?? []) === 0))
        <div class="text-center text-gray-400 py-8">No Data Available</div>
        <div class="text-center">
            <button id="edit_button_doc"
                onclick="document.getElementById('personalInfoForm').classList.remove('hidden'); this.classList.add('hidden')"
                class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
            >Add Document</button>
        </div>
    @else
        <!-- Display Mode -->
        <div id="personalInfoDisplay" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <span class="block text-gray-500 text-sm">User ID</span>
                <span class="font-medium text-gray-900">{{ $data->user_id ?? 'N/A' }}</span>
            </div>

            @forelse($data->documents as $doc)
                <div class="col-span-1 md:col-span-2 lg:col-span-3 border-t pt-4 mt-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <span class="block text-gray-500 text-sm">Document Type</span>
                            <span class="font-medium text-gray-900">{{ $doc->doc_type ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-gray-500 text-sm">Description</span>
                            <span class="font-medium text-gray-900">{{ $doc->doc_desc ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-gray-500 text-sm">Document</span>
                            <div class="mt-1">
                                @if(!empty($doc->doc_url) && Str::endsWith(strtolower($doc->doc_url), ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                    <img src="{{ $doc->doc_url }}" alt="Document Image" class="w-full max-h-96 object-contain rounded-lg border" />
                                @elseif(!empty($doc->doc_url) && Str::endsWith(strtolower($doc->doc_url), ['.pdf']))
                                    <iframe src="{{ $doc->doc_url }}" class="w-full h-96 rounded-lg border" frameborder="0"></iframe>
                                @elseif(!empty($doc->doc_url))
                                    <a href="{{ $doc->doc_url }}" class="text-blue-600 underline" target="_blank">View Document</a>
                                @else
                                    <span class="text-gray-500">N/A</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-gray-500 col-span-1 md:col-span-2 lg:col-span-3">No Documents Available</div>
            @endforelse
        </div>

        <!-- Add Document Button -->
        <button id="edit_button_doc"
            onclick="document.getElementById('personalInfoForm').classList.remove('hidden'); this.classList.add('hidden')"
            class="mt-6 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
        >Add Document</button>
    @endif

    <!-- Document Upload Form -->
    <form 
        id="personalInfoForm" 
        action="{{ route('dashboard_doc_update.user') }}" 
        method="POST" 
        enctype="multipart/form-data"
        class="hidden mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
    >
        @csrf
        @method('POST')

        <input type="hidden" name="user_id" value="{{ $user_id ?? '' }}"/>

        <!-- Document Type -->
        <div>
            <label class="block text-gray-500 text-sm">Document Type</label>
            <select 
                name="doc_type" 
                class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                required
            >
                <option value="" disabled selected>Select Type</option>
                <option value="Phote_Passport_Size">Phote Passport Size</option>
                <option value="Aadhar_Card">Aadhar Card</option>
                <option value="Driver_licence">Driver Licence</option>
                <option value="HighSchool">High School</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Pen_Card">Pen Card</option>
            </select>
        </div>

        <!-- Document Description -->
        <div>
            <label class="block text-gray-500 text-sm">Document Description</label>
            <input 
                type="text" 
                name="doc_desc" 
                class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                placeholder="Enter Description"
                required
            />
        </div>

        <!-- Upload Document -->
        <div>
            <label class="block text-gray-500 text-sm">Upload Document</label>
            <input 
                type="file" 
                name="doc_url" 
                accept=".jpg,.jpeg,.png,.gif,.webp,.pdf"
                class="mt-1 w-full border border-gray-300 rounded px-3 py-2"
                required
            />
        </div>

        <!-- Action Buttons -->
        <div class="col-span-1 md:col-span-2 lg:col-span-3 flex gap-4 mt-4">
            <button 
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
            >Save</button>

            <button 
                type="button"
                onclick="document.getElementById('personalInfoForm').classList.add('hidden'); document.querySelector('#edit_button_doc').classList.remove('hidden')"
                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition"
            >Cancel</button>
        </div>
    </form>
</div>


{{-- <div class="max-w-2xl mx-auto p-6 bg-white rounded-xl shadow-md mt-8">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Personal Information</h2>
    @if(!isset($data) || empty($data) || (is_countable($data) && count($data) === 0))
        <div class="text-center text-gray-400 py-8">No Data Available</div>
    @else
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <span class="font-semibold text-gray-600 w-32">User ID:</span>
            <span class="text-gray-900">{{ $data->user_id ?? 'N/A' }}</span>
        </div>
        @forelse($data->documents ?? [] as $doc)
            <div class="border-t pt-4 mt-4">
                <div class="flex flex-col sm:flex-row sm:items-center">
                    <span class="font-semibold text-gray-600 w-32">Document Type:</span>
                    <span class="text-gray-900">{{ $doc->doc_type ?? 'N/A' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center">
                    <span class="font-semibold text-gray-600 w-32">Description:</span>
                    <span class="text-gray-900">{{ $doc->doc_desc ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="font-semibold text-gray-600">Document:</span>
                    <div class="mt-2">
                        @if(!empty($doc->doc_url) && Str::endsWith(strtolower($doc->doc_url), ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                            <img src="{{ $doc->doc_url }}" alt="Document Image" class="w-full max-h-96 object-contain rounded-lg border" />
                        @elseif(!empty($doc->doc_url) && Str::endsWith(strtolower($doc->doc_url), ['.pdf']))
                            <iframe src="{{ $doc->doc_url }}" class="w-full h-96 rounded-lg border" frameborder="0"></iframe>
                        @elseif(!empty($doc->doc_url))
                            <a href="{{ $doc->doc_url }}" class="text-blue-600 underline" target="_blank">View Document</a>
                        @else
                            <span class="text-gray-500">N/A</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-gray-500 mt-4">N/A</div>
        @endforelse
    </div>
     @endif

</div> --}}

