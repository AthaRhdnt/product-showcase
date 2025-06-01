<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    sidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen') ?? 'true'),
    darkMode: localStorage.getItem('theme') === 'dark'
}" x-init="document.documentElement.classList.toggle('dark', darkMode);
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

        {{-- <title>{{ config('app.name', 'Showcase') }}</title> --}}
        <title>{{ __('Showcase') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
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

    <body
        class="min-h-screen font-sans antialiased transition-colors duration-300 duration-300 bg-gray-100 dark:bg-gray-900">
        <div class="flex h-screen overflow-hidden">
            {{-- Sidebar --}}
            @include('layouts.sidebar')

            {{-- Main content --}}
            <div class="flex-1 flex flex-col overflow-hidden">

                {{-- Navbar --}}
                @include('layouts.navbar')

                {{-- Page Content --}}
                <main class="flex-1 overflow-y-auto p-4">

                    {{-- Page Header --}}
                    @isset($header)
                        <header
                            class="transition-colors duration-300 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-1">
                            <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <div
                        class="transition-colors duration-300 bg-white dark:bg-gray-800 shadow sm:rounded-lg rounded-lg
            flex flex-col
            max-h-[69.5vh]">

                        @isset($data_control)
                            <header
                                class="flex-shrink-0 transition-colors duration-300 bg-white dark:bg-gray-800 shadow p-5 px-4 sm:px-6 lg:px-8 rounded-t-lg">
                                {{ $data_control }}
                            </header>
                        @endisset

                        <div class="flex-1 overflow-y-auto px-5 pb-5">
                            {{ $slot }}
                        </div>
                    </div>

                </main>
            </div>
        </div>
    </body>

</html>
