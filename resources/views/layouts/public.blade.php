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
    :class="{ 'dark': darkMode }" class="scroll-smooth">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ config('app.name', 'Product Showcase Catalog') }}</title>

        <!-- Fonts & Styles -->
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Alpine.js + Collapse plugin -->
        <script src="//unpkg.com/alpinejs" defer></script>
        <script src="//unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>

        <!-- Lightbox2 -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

        <script>
            if (localStorage.theme === 'dark' ||
                (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Alpine store for compare count -->
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('compare', {
                    count: {{ count(session('compare', [])) }},
                    setCount(n) {
                        this.count = n;
                    },
                    increment() {
                        this.count++;
                    },
                    decrement() {
                        if (this.count > 0) this.count--;
                    }
                });
            });
        </script>

        <script>
            function priceFilter() {
                return {
                    min: {{ request()->has('price_min') ? (int) request('price_min') : 'null' }},
                    max: {{ request()->has('price_max') ? (int) request('price_max') : 'null' }},

                    init() {
                        this.adjustMax();
                        this.adjustMin();
                    },

                    formatNumber(value) {
                        if (value === null || value === '') return '';
                        return new Intl.NumberFormat().format(value);
                    },

                    parseNumber(value) {
                        const cleaned = value.toString().replace(/[^\d]/g, '');
                        return cleaned ? Number(cleaned) : null;
                    },

                    updateMin(value) {
                        this.min = this.parseNumber(value);
                        this.adjustMax();
                    },

                    updateMax(value) {
                        this.max = this.parseNumber(value);
                        this.adjustMin();
                    },

                    adjustMax() {
                        if (this.min !== null && (this.max === null || this.max < this.min)) {
                            this.max = this.min;
                        }
                    },

                    adjustMin() {
                        if (this.max !== null && this.min !== null && this.min > this.max) {
                            this.min = this.max;
                        }
                    }
                }
            }
        </script>

        <style>
            /* Ensure all tappable elements have comfortable hit areas */
            button, a, .nav-link {
                @apply p-2;
            }
            
            /* Nav link underline effect */
            .nav-link {
                position: relative;
                transition: color 0.3s ease;
            }

            .nav-link::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                height: 2px;
                width: 0%;
                background-color: #6366f1;
                /* indigo-500 */
                transition: width 0.3s ease;
            }

            .nav-link:hover::after,
            .nav-link.active::after {
                width: 100%;
            }
        </style>
    </head>

    <body :class="{ 'overflow-hidden': sidebarOpen }"
        class="bg-white dark:bg-gray-900 text-base leading-relaxed text-gray-800 dark:text-gray-100 font-inter antialiased min-h-screen flex flex-col">
        <!-- Header -->
        <header
            class="sticky top-0 z-50 bg-white/90 dark:bg-gray-800/90 backdrop-blur-md shadow-sm border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex flex-wrap items-center justify-between">
                <!-- Mobile sidebar toggle -->
                <button
                    class="lg:hidden text-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded"
                    @click="sidebarOpen = !sidebarOpen" aria-label="Toggle sidebar"
                    :aria-expanded="sidebarOpen.toString()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Logo -->
                <a href="{{ route('public.index') }}"
                    class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                    {{ __('Product Showcase Catalog') }}
                </a>

                <!-- Navigation & Theme Toggle -->
                <nav class="flex items-center gap-6 text-sm font-medium">
                    <!-- Dark mode toggle -->
                    <button @click="darkMode = !darkMode"
                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded"
                        aria-label="Toggle dark mode">
                        <svg x-cloak x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m8.485-8.485l-.707.707M3 12H2m1.515-4.243l.707.707M20.485 4.515l-.707.707M12 5a7 7 0 100 14 7 7 0 000-14z" />
                        </svg>
                        <svg x-cloak x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
                        </svg>
                    </button>

                    <!-- Links -->
                    <a href="{{ route('public.index') }}"
                        class="nav-link {{ request()->routeIs('public.index') ? 'text-indigo-600 font-semibold active' : 'text-gray-700 dark:text-gray-300 hover:text-indigo-600' }}">
                        Home
                    </a>

                    <div x-data>
                        <a href="{{ route('public.compare') }}"
                            class="nav-link relative {{ request()->routeIs('public.compare') ? 'text-indigo-600 font-semibold active' : 'text-gray-700 dark:text-gray-300 hover:text-indigo-600' }}">
                            Compare
                            <template x-if="$store.compare.count > 0">
                                <span
                                    class="absolute -top-2 -right-3 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full shadow"
                                    x-text="$store.compare.count"></span>
                            </template>
                        </a>
                    </div>

                    @auth
                        <a href="{{ url('/admin/dashboard') }}"
                            class="px-3 py-1 rounded border text-sm border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-3 py-1 rounded border text-sm border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Log in
                        </a>
                    @endauth
                </nav>
            </div>
        </header>

        <!-- Sidebar (Mobile Overlay) -->
        <div class="lg:hidden fixed inset-0 z-40 overflow-hidden" x-show="sidebarOpen" x-transition>
            <div class="absolute inset-0 bg-black bg-opacity-50" @click="sidebarOpen = false" aria-hidden="true"></div>
            <aside class="absolute top-16 left-0 h-[calc(100%-4rem)] w-3/4 bg-white dark:bg-gray-800 p-4 overflow-y-auto z-50"
                tabindex="-1" aria-label="Filters Sidebar">
                @include('public.partials.filters')
            </aside>
        </div>

        <!-- Main Content -->
        <div class="flex flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 gap-6">
            <!-- Sidebar (Desktop) -->
            <aside class="hidden lg:block w-1/4" aria-label="Filters Sidebar">
                @include('public.partials.filters')
            </aside>

            <!-- Page Content -->
            <main class="w-full lg:w-3/4 space-y-6">
                @yield('content')
            </main>
        </div>

        <!-- Footer -->
        <footer
            class="mt-auto bg-gray-100 dark:bg-gray-900 border-t border-gray-300 dark:border-gray-700 py-4 text-center text-gray-600 dark:text-gray-400 text-sm">
            &copy; {{ date('Y') }} Product Showcase Catalog. All rights reserved.
        </footer>
    </body>

</html>
