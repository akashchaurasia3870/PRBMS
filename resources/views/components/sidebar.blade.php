<div id="sidebar" class="h-[100%] bg-gray-50 border border-gray-300 shadow-md flex flex-col px-6 py-4 overflow-y-auto no-scrollbar" style="width: 25%">

<div class="border-b border-gray-200 pb-4 mb-6">
    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
        <div class="bg-blue-600 text-white rounded-full p-2">
            <!-- You can replace this with an SVG logo -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4">
                </path>
            </svg>
        </div>
        <span class="text-2xl font-extrabold text-gray-800 group-hover:text-blue-600 transition">
            Dashboard
        </span>
    </a>
</div>


    {{-- Navigation --}}
<nav class="flex-1 p-4 bg-white rounded-2xl shadow-lg space-y-4">

    <ul class="space-y-4">

        <li class="bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
            <span class="block text-lg font-semibold text-gray-800 px-4 py-3 border-b border-gray-200">Users</span>
            <ul class="pl-4 py-2 space-y-1">
                <li>
                    <a href="{{ route('dashboard_create.user') }}"
                       class="block rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 px-3 py-2 transition">
                        ➕ Create User
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard_list.user') }}"
                       class="block rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 px-3 py-2 transition">
                        📃 List Users
                    </a>
                </li>
            </ul>
        </li>

        <li class="bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
            <span class="block text-lg font-semibold text-gray-800 px-4 py-3 border-b border-gray-200">Roles</span>
            <ul class="pl-4 py-2 space-y-1">
                <li>
                    <a href="{{ route('dashboard_create.roles') }}"
                       class="block rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 px-3 py-2 transition">
                        ➕ Create Role
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard_list.roles') }}"
                       class="block rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 px-3 py-2 transition">
                        📃 List Roles
                    </a>
                </li>
            </ul>
        </li>

        <li class="bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
            <span class="block text-lg font-semibold text-gray-800 px-4 py-3 border-b border-gray-200">Attendance</span>
            <ul class="pl-4 py-2 space-y-1">
                <li>
                    <a href="{{ route('dashboard_mark.mark_attendance') }}"
                       class="block rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 px-3 py-2 transition">
                        ➕ Mark Attendance
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard_list.user_attendance') }}"
                       class="block rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 px-3 py-2 transition">
                        📃 List Users Attendance
                    </a>
                </li>
            </ul>
        </li>

        <li class="bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
            <span class="block text-lg font-semibold text-gray-800 px-4 py-3 border-b border-gray-200">Leave</span>
            <ul class="pl-4 py-2 space-y-1">
                <li>
                    <a href="{{ route('dashboard_leave.leave_request_view') }}"
                       class="block rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 px-3 py-2 transition">
                        ➕ Apply Leave
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard_leave.leave_request') }}"
                       class="block rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 px-3 py-2 transition">
                        📃 Leave Requests
                    </a>
                </li>
            </ul>
        </li>

        <li class="bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
            <span class="block text-lg font-semibold text-gray-800 px-4 py-3 border-b border-gray-200">Payroll</span>
            <ul class="pl-4 py-2 space-y-1">
                <li>
                    <a href="{{ route('dashboard_salary.create') }}"
                       class="block rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 px-3 py-2 transition">
                        ➕ Create Structure
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard_salary.index') }}"
                       class="block rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 px-3 py-2 transition">
                        📃 View Structure
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard_payroll.index') }}"
                       class="block rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 px-3 py-2 transition">
                        📃 View Payroll
                    </a>
                </li>
                {{-- <li>
                    <a href="{{ route('dashboard_payroll.edit') }}"
                       class="block rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 px-3 py-2 transition">
                        📃 Edit Payroll
                    </a>
                </li> --}}
                <li>
                    <a href="{{ route('dashboard_payroll.generateForm') }}"
                       class="block rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 px-3 py-2 transition">
                        📃 Generate Payroll
                    </a>
                </li>
                
            </ul>
        </li>

        <li class="bg-gray-50 border border-gray-200 rounded-xl">
            <a href="{{ route('profile.show') }}"
               class="block text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-lg px-4 py-3 transition font-medium">
               👤 Profile
            </a>
        </li>

        <li class="bg-gray-50 border border-gray-200 rounded-xl">
            <a href="#"
               class="block text-gray-600 hover:bg-red-50 hover:text-red-600 rounded-lg px-4 py-3 transition font-medium">
               🚪 Logout
            </a>
        </li>

    </ul>
</nav>


    {{-- Footer --}}
    <div class="mt-4 text-xs text-gray-400 border-t border-gray-300 pt-2">
        &copy; {{ date('Y') }} PRBMS
    </div>

</div>
