<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-9">
    <meta name="viewport" content="width=device-width, initial-scale=0">

    <title>{{ config('app.name', 'XMX Blog') }}</title>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <!-- 🔥 APLICA O TEMA ANTES DE RENDERIZAR (SEM FLICKER) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

            const isDark = savedTheme === 'dark' || (!savedTheme && prefersDark);

            if (isDark) {
                document.documentElement.classList.add("dark");
            } else {
                document.documentElement.classList.remove("dark");
            }
        })();
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            @apply bg-gray-50 dark:bg-gray-900;
        }
    </style>
</head>
<body class="text-gray-800 dark:bg-gray-900 dark:text-gray-200 transition-colors min-h-screen flex flex-col duration-300">

    @if($showHeader)
    <header class="sticky top-0 z-50 bg-white shadow-lg dark:bg-gray-800 dark:shadow-2xl transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-center items-center relative">
            
            <a href={{ url('/') }} class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400 tracking-tight">
                XMX Blog
            </a>

            <button id="themeToggle" class="cursor-pointer absolute right-4 top-1/2 transform -translate-y-1/2 text-indigo-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 p-2 rounded-full transition duration-150">
                <svg id="sun-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>

                <svg id="moon-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </button>
        </div>
    </header>
    @endif

    @if($showHeader)
    <main class="dark:bg-gray-900 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1">
    @else
    <main class="dark:bg-gray-900 max-w-7xl mx-auto flex-1">
    @endif
        {{ $slot }}
    </main>

    @if($showFooter)
    <footer class="bg-white dark:bg-gray-900 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center text-gray-400">
            <p>&copy; 2025 XMX Blog. Todos os direitos reservados.</p>
            <div class="mt-4 space-x-4">
                <a href="#" class="hover:text-indigo-900 hover:dark:text-white transition duration-150">Termos de Uso</a>
                <span class="text-gray-600">|</span>
                <a href="#" class="hover:text-indigo-900 hover:dark:text-white transition duration-150">Política de Privacidade</a>
            </div>
        </div>
    </footer>
    @endif

    <!-- 🔥 SCRIPT FINAL PARA TOGGLE + ÍCONES -->
    <script>
        function updateIcons() {
            const isDark = document.documentElement.classList.contains("dark");
            document.getElementById("sun-icon")?.classList.toggle("hidden", isDark);
            document.getElementById("moon-icon")?.classList.toggle("hidden", !isDark);
        }

        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.toggle("dark");

            localStorage.setItem("theme", isDark ? "dark" : "light");
            updateIcons();
        }

        document.addEventListener("DOMContentLoaded", () => {
            updateIcons();

            const btn = document.getElementById("themeToggle");
            if (btn) btn.addEventListener("click", toggleTheme);
        });
    </script>

</body>
</html>