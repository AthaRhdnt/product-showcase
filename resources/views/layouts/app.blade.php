<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    sidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen') ?? 'true'),
    darkMode: localStorage.getItem('theme') === 'dark'
}"
    x-init="document.documentElement.classList.toggle('dark', darkMode);
    $watch('sidebarOpen', value => {
        localStorage.setItem('sidebarOpen', value);
        document.documentElement.style.setProperty('--sidebar-width', value ? '16rem' : '4rem');
    });
    $watch('darkMode', value => localStorage.setItem('theme', value ? 'dark' : 'light'));"
    x-effect="
        document.documentElement.classList.toggle('dark', darkMode);
        localStorage.setItem('theme', darkMode ? 'dark' : 'light');
    "
    :class="{ 'dark': darkMode }">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('Showcase') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="//unpkg.com/alpinejs" defer></script>
        <script>
            if (localStorage.theme === 'dark' ||
                (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
        <script>
            const sidebarOpen = JSON.parse(localStorage.getItem('sidebarOpen') ?? 'true');
            document.documentElement.style.setProperty('--sidebar-width', sidebarOpen ? '16rem' : '4rem');
        </script>
    </head>

    <body class="min-h-screen font-sans antialiased bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <div class="flex h-screen overflow-hidden">
            @include('layouts.sidebar')

            <div class="flex-1 flex flex-col overflow-hidden">
                @include('layouts.navbar')

                <main class="flex-1 overflow-y-auto p-4">
                    @isset($header)
                        <header class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-4">
                            <div class="max-w-7xl mx-auto py-4 px-6">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg flex flex-col max-h-[69.5vh]">
                        @isset($data_control)
                            <div class="flex-shrink-0 bg-white dark:bg-gray-800 shadow px-6 py-4 rounded-t-lg">
                                {{ $data_control }}
                            </div>
                        @endisset

                        <div class="flex-1 overflow-y-auto px-6 pb-6">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
