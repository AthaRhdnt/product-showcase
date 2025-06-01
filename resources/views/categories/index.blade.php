<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <x-slot name="data_control">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Left controls -->
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <!-- Search input -->
                <form method="GET" action="" class="flex items-center">
                    <x-text-input type="text" name="search" placeholder="Search..." class="w-48" />
                </form>

                <!-- Entries dropdown -->
                <form method="GET" action="" id="entries-form" class="flex items-center">
                    <x-select name="entries" id="entries" class="w-28" onchange="document.getElementById('entries-form').submit();">
                        <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
                    </x-select>
                </form>
            </div>

            <!-- Add product button -->
            <a href="{{ route('admin.categories.create') }}">
                    <x-button>
                        + Add Category
                    </x-button>
                </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="text-green-600 mb-4">{{ session('success') }}</div>
    @endif

    <div
        class="overflow-hidden transition-colors duration-300 bg-white dark:bg-gray-800 shadow text-gray-800 dark:text-gray-200 m-5">
        <table class="min-w-full border border-gray-400 dark:border-gray-700">
            <thead>
                <tr class="border border-gray-400 dark:border-gray-700">
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $cateogry)
                    <tr
                        class="border border-gray-400 dark:border-gray-700 bg-white dark:bg-gray-800 odd:bg-gray-50 dark:odd:bg-gray-700">
                        <td class="px-4 py-2">{{ $cateogry->name }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.categories.edit', $cateogry) }}">
                                <x-secondary-button>
                                    Edit
                                </x-secondary-button>
                            </a> |
                            <form action="{{ route('admin.categories.destroy', $cateogry) }}" method="POST"
                                class="inline-block" onsubmit="return confirm('Delete this cateogry?')">
                                @csrf @method('DELETE')
                                <x-danger-button class="text-red-600">Delete</x-danger-button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</x-app-layout>
