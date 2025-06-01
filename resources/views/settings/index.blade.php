<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div class="text-green-600 mb-4">{{ session('success') }}</div>
    @endif

    <div
        class="overflow-hidden transition-colors duration-300 bg-white dark:bg-gray-800 shadow text-gray-800 dark:text-gray-200 m-5">
        
    </div>
</x-app-layout>
