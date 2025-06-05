@extends('layouts.public')

@section('content')
    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Filters Sidebar (commented out) --}}

        <!-- Products Grid -->
        <section class="w-full lg:w-3/4">
            <!-- Header with Compare Button -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Products</h2>
                <a href="{{ route('public.compare') }}"
                    class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded transition">
                    <span x-text="'View Compare (' + $store.compare.count + ')'"></span>
                </a>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm overflow-hidden flex flex-col">
                        {{-- <img src="{{ $product->getFirstMediaUrl('images', 'thumb') ?? 'https://via.placeholder.com/300x200' }}"
                            alt="{{ $product->name }}" class="w-full h-48 object-cover"> --}}
                        @php
                            $thumbnail = $product->images->firstWhere('is_thumbnail', 1);
                        @endphp
                        @if ($thumbnail)
                            <img src="{{ asset($thumbnail->path) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        @else
                            <img src='https://via.placeholder.com/300x200' alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        @endif

                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Rp.
                                {{ number_format($product->price) }}</p>

                            <a href="{{ route('public.show', $product->id) }}"
                                class="mt-3 inline-block bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 rounded transition">
                                View Details
                            </a>

                            <form x-data="{ checked: {{ in_array($product->id, session('compare', [])) ? 'true' : 'false' }} }"
                                x-on:change="
                            fetch('{{ route('public.compare.toggle', $product->id) }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                }
                            }).then(response => response.json())
                              .then(data => {
                                  checked = (data.status === 'added');
                                  if (data.status === 'added') {
                                      $store.compare.increment();
                                  } else {
                                      $store.compare.decrement();
                                  }
                              });
                          "
                                class="mt-4 flex items-center cursor-pointer select-none">
                                @csrf
                                <label class="inline-flex items-center cursor-pointer w-full">
                                    <input type="checkbox" name="compare" class="sr-only peer" :checked="checked">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-checked:bg-indigo-600 rounded-full peer transition-all duration-300">
                                    </div>
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300"
                                        x-text="checked ? 'Remove' : 'Compare'"></span>
                                </label>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 col-span-full">No products found.</p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->withQueryString()->links() }}
            </div>
        </section>
    </div>
@endsection
