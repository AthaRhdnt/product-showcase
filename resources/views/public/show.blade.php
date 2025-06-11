@extends('layouts.public')

@section('content')
    <div class="max-w-4xl mx-auto px-4">
        <x-back-button/>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm overflow-hidden">
            {{-- Image Gallery --}}
            @if ($product->images->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
                    @foreach ($product->images as $image)
                        <div class="relative group">
                            <a href="{{ asset($image->path) }}" data-lightbox="product-gallery" data-title="{{ $product->name }}">
                                <img src="{{ asset($image->path) }}"
                                    alt="{{ $image->is_thumbnail ? 'Thumbnail' : 'Product Image' }}"
                                    class="rounded-lg shadow-md object-cover w-full h-64 border border-gray-300 dark:border-gray-600 transition-transform group-hover:scale-105">
                            </a>
                            @if ($image->is_thumbnail)
                                <span class="absolute top-2 left-2 bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded">
                                    Thumbnail
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    No media available for this product.
                </div>
            @endif

            {{-- Product Info --}}
            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $product->name }}</h1>
                <p class="text-xl text-gray-700 dark:text-gray-300 mt-2">
                    Rp. {{ number_format($product->price) }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Category: {{ $product->category->name }}
                </p>

                @if ($product->attributes->isNotEmpty())
                    <div class="mt-6">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Attributes</h2>
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-1">
                            @foreach ($product->attributes as $attribute)
                                <li>{{ $attribute->name }}: {{ $attribute->pivot->value }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
