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


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />
        <div class="min-h-screen flex flex-col bg-gray-100">

            {{-- Navigation Header --}}
            @livewire('navigation-menu')
        
            {{-- Main Layout --}}
            <div class="flex flex-1">
        
                {{-- Sidebar --}}
                <section class="">
                </section>
                <x-sidebar class="w-1/5" />
        
                {{-- Page Content --}}
                <main class="flex-1 p-4 h-screen overflow-y-scroll bg-white-100">
                    {{ $slot }}
                </main>
        
            </div>
        
        </div>
        

        @stack('modals')

        @livewireScripts
    </body>


</html>
