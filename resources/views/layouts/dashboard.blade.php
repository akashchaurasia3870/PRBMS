<x-app-layout title="Dashboard">
    <aside>
        <x-sidebar/>
    </aside>
    <main class="flex h-screen bg-gray-100">
        {{$slot}}
    </main>
</x-app-layout>
