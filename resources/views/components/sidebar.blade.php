<div class="h-screen bg-white shadow-lg flex flex-col p-6">

    {{-- Logo / Brand --}}
    <div class="text-2xl font-extrabold text-blue-600">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg px-4 py-2 transition">
            {{-- <span>🏠</span> --}}
            <span>Dashboard</span>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex flex-col items-start justify-start">
        <ul>
            <li>
                <button 
                    class="flex justify-start w-full text-left items-center px-4 py-2 rounded bg-gray-200 hover:bg-gray-400"
                    onclick="
                        let el = document.getElementById('user_c');
                        let icon = document.getElementById('icon-user_c');
                        el.classList.toggle('hidden');
                        icon.classList.toggle('rotate-180');
                    "
                >
                    <span class="mr-2">Usres</span>
                    <span>
                        <i id="icon-user_c" class="fa fa-chevron-down transition-transform duration-300"></i>
                    </span>
                </button>
        
                <ul id="user_c" class="hidden pl-6 space-y-1">
                    <li class="bg-gray-900">
                        <a href="{{ route('users_cstm.index') }}" class="flex items-center space-x-2 text-gray-700 bg-gray-400 hover:bg-blue-50 hover:text-blue-600 rounded-lg px-4 py-2 transition">
                            <span>Create User</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users_cstm.index') }}" class="flex items-center space-x-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg px-4 py-2 transition">
                            <span>List Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users_cstm.index') }}" class="flex items-center space-x-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg px-4 py-2 transition">
                            <span>Edit Users</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>       
                    <a href="{{ route('profile.show') }}" class="flex items-center space-x-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg px-4 py-2 transition">
                        {{-- <span>👤</span> --}}
                        <span>Profile</span>
                    </a>
            <li>
              </li>
                <a href="#" class="flex items-center space-x-2 text-gray-700 hover:bg-red-50 hover:text-red-500 rounded-lg px-4 py-2 transition">
                    {{-- <span>🚪</span> --}}
                    <span>Logout</span>
                </a>
            
            </li>
        
        </ul>
    </nav>

    {{-- Footer (optional) --}}
    <div class="mt-auto text-xs text-gray-400">
        &copy; {{ date('Y') }} PRBMS
    </div>

</div>
