<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'PRBMS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
        <script src="//unpkg.com/alpinejs" defer></script>



        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />
        {{-- Sidebar --}}
        <div class="flex flex-row h-[100vh] w-[100vw] bg-gray-700 overflow-hidden">
            <x-sidebar/>
            <div class="flex flex-col bg-gray-100 w-[100%] h-[100%]">
    
                {{-- Navigation Header --}}
                @livewire('navigation-menu')
                {{-- Main Layout --}}
                <div class="flex flex-1">
                    {{-- Page Content --}}
                    <main class="flex-1 pt-2 pb-4 px-2 bg-white-100 h-[92vh]">
                        {{ $slot }}
                    </main>
            
                </div>
            
            </div>    
        </div>
        
        @stack('modals')

        @livewireScripts
    </body>

    <script>
        const sidebar = document.getElementById('sidebar');
        const menuToggle = document.getElementById('menuToggle');
    
        let isOpen = sessionStorage.getItem('sidebarState') === 'true';

        if (!isOpen) {
            sidebar.style.display = 'none';
        }

        menuToggle.addEventListener('click', () => {
            if (isOpen) {
            sidebar.style.display = 'none';
            } else {
            sidebar.style.display = 'flex';
            }
            isOpen = !isOpen;
            sessionStorage.setItem('sidebarState', isOpen);
        });
    </script>

</html>
