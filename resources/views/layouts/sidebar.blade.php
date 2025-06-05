{{-- <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" class="fixed md:static z-40 inset-y-0 left-0 w-64 transform bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 transition-transform duration-200 ease-in-out">
    <div class="h-full overflow-y-auto pt-5">
        <div class="flex justify-between items-center px-4 mb-4">
            <span class="text-lg font-semibold text-gray-800 dark:text-white">Navigation</span>
            <button @click="sidebarOpen = false" class="md:hidden text-gray-600 dark:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div> --}}
{{-- <aside :class="sidebarOpen ? 'w-64' : 'w-16 md:w-64'"
    class="bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 transform transition-all duration-300 ease-in-out">
    <div class="h-full overflow-y-auto pt-5">
        <div class="flex justify-center items-center px-4 mb-4">
            <div>
                <a href="{{ route('admin.dashboard') }}">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                </a>
            </div>
        </div> --}}
{{-- <aside 
    :class="sidebarOpen ? 'w-64' : 'w-16'" 
    class="bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 transform transition-all duration-300 ease-in-out"> --}}
<aside :class="sidebarOpen ? 'w-64' : 'w-16'"
    class="transition-all duration-300 ease-in-out bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700"
    style="width: var(--sidebar-width)">
    <div class="h-full overflow-y-auto pt-5">
        <div class="flex justify-center items-center px-4 mb-4">
            <a href="{{ route('public.index') }}">
                <svg class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5"/>
                </svg>
            </a>
        </div>

        <nav class="space-y-2 px-4">
            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                <x-application-logo class="block h-6 w-auto fill-current text-gray-800 dark:text-gray-200 mr-5" />
                {{ __('Dashboard') }}
            </x-nav-link>
        </nav>
        <nav class="space-y-2 px-4">
            <x-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                <svg class="block w-auto h-6 text-gray-800 dark:text-gray-200 mr-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 10V6a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v4m3-2 .917 11.923A1 1 0 0 1 17.92 21H6.08a1 1 0 0 1-.997-1.077L6 8h12Z" />
                </svg>
                {{ __('Products') }}
            </x-nav-link>
        </nav>
        <nav class="space-y-2 px-4">
            <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                <svg class="block w-auto h-6 text-gray-800 dark:text-gray-200 mr-5" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 19V6a1 1 0 0 1 1-1h4.032a1 1 0 0 1 .768.36l1.9 2.28a1 1 0 0 0 .768.36H16a1 1 0 0 1 1 1v1M3 19l3-8h15l-3 8H3Z" />
                </svg>
                {{ __('Categories') }}
            </x-nav-link>
        </nav>
        <nav class="space-y-2 px-4">
            <x-nav-link :href="route('admin.attributes.index')" :active="request()->routeIs('admin.attributes.*')">
                <svg class="block w-auto h-6 text-gray-800 dark:text-gray-200 mr-5" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                        d="M20 6H10m0 0a2 2 0 1 0-4 0m4 0a2 2 0 1 1-4 0m0 0H4m16 6h-2m0 0a2 2 0 1 0-4 0m4 0a2 2 0 1 1-4 0m0 0H4m16 6H10m0 0a2 2 0 1 0-4 0m4 0a2 2 0 1 1-4 0m0 0H4" />
                </svg>
                {{ __('Attributes') }}
            </x-nav-link>
        </nav>
        <nav class="space-y-2 px-4">
            <x-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">
                <svg class="block w-auto h-6 text-gray-800 dark:text-gray-200 mr-5" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 13v-2a1 1 0 0 0-1-1h-.757l-.707-1.707.535-.536a1 1 0 0 0 0-1.414l-1.414-1.414a1 1 0 0 0-1.414 0l-.536.535L14 4.757V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v.757l-1.707.707-.536-.535a1 1 0 0 0-1.414 0L4.929 6.343a1 1 0 0 0 0 1.414l.536.536L4.757 10H4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h.757l.707 1.707-.535.536a1 1 0 0 0 0 1.414l1.414 1.414a1 1 0 0 0 1.414 0l.536-.535 1.707.707V20a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-.757l1.707-.708.536.536a1 1 0 0 0 1.414 0l1.414-1.414a1 1 0 0 0 0-1.414l-.535-.536.707-1.707H20a1 1 0 0 0 1-1Z" />
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                </svg>
                {{ __('Settings') }}
            </x-nav-link>
        </nav>
    </div>
</aside>
