<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <x-slot name="data_control">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Left controls -->
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <!-- Search input -->
                <form method="GET" action="" class="flex items-center">
                    <x-text-input type="text" name="search" placeholder="Search..." class="w-48" />
                    <x-primary-button type="submit" class="m-1">Search</x-primary-button>
                </form>

                <!-- Entries dropdown -->
                <form method="GET" action="" id="entries-form" class="flex items-center">
                    <x-select name="entries" id="entries" class="w-28"
                        onchange="document.getElementById('entries-form').submit();">
                        <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
                    </x-select>
                </form>

                <form>
                    <a>
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 20V10m0 10-3-3m3 3 3-3m5-13v10m0-10 3 3m-3-3-3 3" />
                        </svg>
                    </a>

                </form>
            </div>

            <!-- Add product button -->
            <a href="{{ route('admin.products.create') }}">
                <x-button>
                    + Add Product
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
                    <th class="p-2">Name</th>
                    <th class="p-2">Price</th>
                    <th class="p-2">Category</th>
                    <th class="p-2">Description</th>
                    <th class="p-2">Thumbnail</th>
                    <th class="p-2">Attributes</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr
                        class="border border-gray-400 dark:border-gray-700 bg-white dark:bg-gray-800 odd:bg-gray-50 dark:odd:bg-gray-700">
                        <td class="px-4 py-2" width="10%">{{ $product->name }}</td>
                        <td class="px-4 py-2" width="5%" align="right">Rp. {{ number_format($product->price) }}
                        </td>
                        <td class="px-4 py-2" width="5%" align="center">{{ $product->category->name }}</td>
                        <td class="px-4 py-2" width="15%">{{ Str::limit($product->description, 60) }}</td>

                        {{-- Show thumbnail image --}}
                        <td class="px-4 py-2" width="10%" align="center">
                            @php
                                $thumbnail = $product
                                    ->getMedia('images')
                                    ->firstWhere('custom_properties.thumbnail', true);
                            @endphp

                            @if ($thumbnail)
                                <img src="{{ $thumbnail->getUrl('thumb') }}" class="w-16 h-16 object-cover rounded">
                            @else
                                <span class="text-sm text-gray-500 italic">No thumbnail</span>
                            @endif
                        </td>

                        <td class="px-4 py-2" width="15%">
                            @foreach ($product->attributes as $attribute)
                                <div><strong>{{ $attribute->name }}:</strong> {{ $attribute->pivot->value }}</div>
                            @endforeach
                        </td>

                        <td class="px-4 py-2" width="15%" align="center">
                            <a href="{{ route('admin.products.edit', $product) }}">
                                <x-secondary-button>Edit</x-secondary-button>
                            </a> |
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                class="inline-block" onsubmit="return confirm('Delete this product?')">
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
        {{ $products->links() }}
    </div>
</x-app-layout>
