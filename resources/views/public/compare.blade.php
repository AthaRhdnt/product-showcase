@extends('layouts.public')

@section('content')
    <div class="max-w-6xl mx-auto px-4">
        @if (session('compare_back_url'))
            <x-back-button/>
        @endif
        
        <h2 class="text-3xl font-bold mb-8 text-gray-900 dark:text-white">Compare Products</h2>

        @if ($compareProducts->count() > 0)
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                <table class="min-w-full border-collapse table-fixed text-sm text-gray-800 dark:text-gray-300">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-800">
                            <th class="p-4 w-48"></th>
                            @foreach ($compareProducts as $product)
                                <th class="p-4 text-left font-semibold">{{ $product->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-gray-50 dark:bg-gray-900">
                            <th class="p-4 text-left font-medium">Price</th>
                            @foreach ($compareProducts as $product)
                                <td class="border-t border-gray-200 dark:border-gray-700 p-4">Rp {{ number_format($product->price) }}</td>
                            @endforeach
                        </tr>
                        <tr class="bg-gray-50 dark:bg-gray-900">
                            <th class="p-4 text-left font-medium">Category</th>
                            @foreach ($compareProducts as $product)
                                <td class="border-t border-gray-200 dark:border-gray-700 p-4">{{ $product->category->name }}</td>
                            @endforeach
                        </tr>
                        @foreach ($allAttributes as $attribute)
                            <tr class="{{ $loop->even ? 'bg-gray-50 dark:bg-gray-900' : '' }}">
                                <th class="p-4 text-left font-medium">{{ $attribute->name }}</th>
                                @foreach ($compareProducts as $product)
                                    @php
                                        $value = optional($product->attributes->firstWhere('id', $attribute->id))->pivot->value ?? '-';
                                    @endphp
                                    <td class="border-t border-gray-200 dark:border-gray-700 p-4">{{ $value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400">No products in the comparison list.</p>
        @endif
    </div>
@endsection
