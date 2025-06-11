<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ __('Product List') }}
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

            <!-- Add Product -->
            <a href="{{ route('admin.products.create') }}">
                <x-button class="bg-indigo-600 hover:bg-indigo-700">+ Add Product</x-button>
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
                    <th class="p-3 font-semibold text-right">Price</th>
                    <th class="p-3 font-semibold text-center">Category</th>
                    <th class="p-3 font-semibold">Description</th>
                    <th class="p-3 font-semibold text-center">Thumbnail</th>
                    <th class="p-3 font-semibold">Attributes</th>
                    <th class="p-3 font-semibold text-center">Media</th>
                    <th class="p-3 font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr
                        class="border-t border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="p-3">{{ $product->name }}</td>
                        <td class="p-3 text-right">Rp. {{ number_format($product->price) }}</td>
                        <td class="p-3 text-center">{{ $product->category->name }}</td>
                        <td class="p-3">{{ Str::limit($product->description, 60) }}</td>
                        <td class="p-3 text-center">
                            @php $thumbnail = $product->images->firstWhere('is_thumbnail', 1); @endphp
                            @if ($thumbnail)
                                <img src="{{ asset($thumbnail->path) }}" alt="Thumbnail"
                                    class="w-24 h-24 object-cover mx-auto rounded shadow">
                            @else
                                <span class="italic text-gray-400">No image</span>
                            @endif
                        </td>
                        <td class="p-3 space-y-1">
                            @foreach ($product->attributes as $attribute)
                                <div><strong>{{ $attribute->name }}:</strong> {{ $attribute->pivot->value }}</div>
                            @endforeach
                        </td>
                        <td class="p-3 text-center">
                            {{-- <div x-data="{ open: false }" x-init="open = false">
                                <x-primary-button @click="open = true">
                                    View ({{ $product->images->count() }})
                                </x-primary-button>

                                <!-- Modal -->
                                <div x-show="open" x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2 p-6 relative">
                                        <button @click="open = false"
                                            class="absolute top-2 right-2 text-gray-600 dark:text-gray-300 hover:text-red-500">
                                            ✕
                                        </button>
                                        <h2 class="text-xl font-bold mb-4">Media for {{ $product->name }}</h2>
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 max-h-96 overflow-y-auto">
                                            @forelse ($product->images as $image)
                                                <div class="text-center">
                                                    <img src="{{ asset($image->path) }}" alt="Image"
                                                        class="w-full h-32 object-cover rounded shadow">
                                                    @if ($image->is_thumbnail)
                                                        <span class="block mt-1 text-xs text-green-600 dark:text-green-400 font-semibold">Thumbnail</span>
                                                    @endif
                                                </div>
                                            @empty
                                                <p class="col-span-3 text-center text-sm text-gray-500">No media available.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            <div x-data="{ open: false }" x-id="['modal-{{ $product->id }}']">
                                <x-primary-button @click="open = true">
                                    View ({{ $product->images->count() }})
                                </x-primary-button>

                                <template x-if="open" x-on:keydown.escape.window="open = false">
                                    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60"
                                        x-cloak>
                                        <div
                                            class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2 p-6 relative">
                                            <button @click="open = false"
                                                class="absolute top-2 right-2 text-gray-600 dark:text-gray-300 hover:text-red-500">
                                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                            </button>
                                            <h2 class="text-xl font-bold mb-4">Media for {{ $product->name }}</h2>
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 max-h-96 overflow-y-auto">
                                                @forelse ($product->images as $image)
                                                    <div class="text-center">
                                                        <img src="{{ asset($image->path) }}" alt="Image"
                                                            class="w-full h-32 object-cover rounded shadow">
                                                        @if ($image->is_thumbnail)
                                                            <span
                                                                class="block mt-1 text-xs text-green-600 dark:text-green-400 font-semibold">Thumbnail</span>
                                                        @endif
                                                    </div>
                                                @empty
                                                    <p class="col-span-3 text-center text-sm text-gray-500">No media
                                                        available.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </td>
                        <td class="p-3 text-center space-x-2">
                            <a href="{{ route('admin.products.edit', $product) }}">
                                <x-secondary-button>Edit</x-secondary-button>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                class="inline-block" onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <x-danger-button>Delete</x-danger-button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->links('pagination::tailwind') }}
        </div>
    </div>
</x-app-layout>
