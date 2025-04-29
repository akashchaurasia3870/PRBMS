<header class="bg-white shadow flex items-center justify-between px-4 py-3">
    <h1 class="text-xl font-bold">MyApp</h1>

    {{-- Mobile Sidebar Toggle --}}
    <button id="menuToggle" class="text-gray-700 focus:outline-none lg:hidden">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');

        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
        });
    });
</script>
