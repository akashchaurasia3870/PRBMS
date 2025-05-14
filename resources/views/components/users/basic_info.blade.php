<div class="w-full mx-auto bg-white rounded-xl shadow-md overflow-hidden mt-6">
    <h2 class="text-2xl font-semibold mb-4 text-gray-800 px-6 py-4">Basic Details</h2>
    @if(!isset($data) || empty($data) || (is_countable($data) && count($data) === 0))
        <div class="text-center text-gray-400 py-8">No Data Available</div>
    @else
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr class="flex flex-row justify-between">
                <th class="py-4 px-6 font-semibold text-gray-700 text-left flex-1">UserImage</th>
                <th class="py-4 px-6 font-semibold text-gray-700 text-left flex-1">UserName</th>
                <th class="py-4 px-6 font-semibold text-gray-700 text-left flex-1">UserEmail</th>
            </tr>
        </thead>
        <tbody>
            <tr class="flex flex-row justify-between">
                <td class="py-4 px-6 flex-1">
                    <img class="h-20 w-20 rounded-full object-cover border-2 border-indigo-500"
                         src="{{ $data->profile_photo_path ?? asset('images/default-profile.png') }}"
                         alt="User Profile">
                </td>
                <td class="py-4 px-6 text-left flex-1">{{ $data->name ?? 'N/A' }}</td>
                <td class="py-4 px-6 text-left flex-1">{{ $data->email ?? 'N/A' }}</td>
            </tr>
        </tbody>
    </table>
    @endif
</div>