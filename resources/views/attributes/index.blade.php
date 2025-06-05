<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ __('Attribute List') }}
        </h2>
    </x-slot>

    <x-slot name="data_control">
        <div
            class="flex flex-col md:flex-row items-center justify-between gap-4 px-4 py-2 bg-white dark:bg-gray-900 rounded-md shadow-sm">
            <!-- Left controls -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Search -->
                <form method="GET" action="" class="flex items-center gap-2">
                    <x-text-input type="text" name="search" placeholder="Search..." class="w-48" />
                    <x-primary-button>Search</x-primary-button>
                </form>

                <!-- Entries -->
                <form method="GET" action="" id="entries-form" class="flex items-center gap-2">
                    <x-select name="entries" id="entries" class="w-28"
                        onchange="document.getElementById('entries-form').submit();">
                        @foreach ([5, 10, 25, 50, 100] as $entry)
                            <option value="{{ $entry }}" {{ request('entries') == $entry ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </x-select>
                </form>

                <!-- Sort toggle -->
                <form method="GET" action="" class="flex items-center">
                    <input type="hidden" name="sort"
                        value="{{ request('sort') === 'name' ? 'name' : 'created_at' }}">
                    <input type="hidden" name="direction"
                        value="{{ request('direction') === 'asc' ? 'desc' : 'asc' }}">
                    <button type="submit" title="Toggle Sort">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white hover:scale-110 transition-transform"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 20V10m0 10-3-3m3 3 3-3m5-13v10m0-10 3 3m-3-3-3 3" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Add Category -->
            <a href="{{ route('admin.attributes.create') }}">
                <x-button class="bg-indigo-600 hover:bg-indigo-700">+ Add Attribute</x-button>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mt-4 px-4 text-green-600 font-medium">{{ session('success') }}</div>
    @endif

    <!-- Table -->
    <div class="mt-6 px-4 overflow-x-auto">
        <table
            class="min-w-full table-auto border rounded shadow-sm text-sm text-left bg-white dark:bg-gray-800 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100">
                <tr>
                    <th class="p-3 font-semibold">Name</th>
                    <th class="p-3 font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attributes as $attribute)
                    <tr
                        class="border-t border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="p-3">{{ $attribute->name }}</td>
                        <td class="p-3 text-center space-x-2">
                            <a href="{{ route('admin.attributes.edit', $attribute) }}">
                                <x-secondary-button>Edit</x-secondary-button>
                            </a>
                            <form action="{{ route('admin.attributes.destroy', $attribute) }}" method="POST"
                                class="inline-block" onsubmit="return confirm('Delete this attribute?')">
                                @csrf @method('DELETE')
                                <x-danger-button>Delete</x-danger-button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="p-4 text-center text-gray-500 dark:text-gray-400">No attributes found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $attributes->links() }}
        </div>
    </div>
</x-app-layout>
