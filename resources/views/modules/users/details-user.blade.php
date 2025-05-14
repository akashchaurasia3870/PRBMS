<x-app-layout>
    {{-- @php
    dd($data['user_info'])
    @endphp --}}
    <div class="mx-auto bg-white rounded shadow-md p-5 h-[100%] overflow-y-scroll no-scrollbar">
        <x-users.basic_info :data="$data['user_info']" />
        <x-users.address_info :data="$data['contact_info']" />
        <x-users.personal_info :data="$data['documents_info']"/>
        <x-roles.basic_info :data="$data['roles_info']"/>
    </div>
</x-app-layout>
