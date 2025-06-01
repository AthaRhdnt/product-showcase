<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Product') }}
        </h2>
    </x-slot>

    <div class="p-6 max-h-[69.5vh] overflow-y-auto">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $product->name)"
                    required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="price" :value="__('Price')" />
                <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price', $product->price)"
                    required />
                <x-input-error :messages="$errors->get('price')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.products.index') }}">
                    <x-secondary-button>Cancel</x-secondary-button>
                </a>
                <x-primary-button type="submit">Save</x-primary-button>
            </div>
        </form>
        {{-- <form method="POST" action="{{ route('admin.products.update', $product) }}">
            @csrf
            @method('PUT')

            <input type="text" name="price" value="12345">
            <button type="submit">Test Submit</button>
        </form> --}}
    </div>

    {{-- <script>
        // function formatPriceInput(input) {
        //     let value = input.value.replace(/[^\d]/g, '');
        //     if (!value) {
        //         input.value = '';
        //         return;
        //     }
        //     input.value = new Intl.NumberFormat('id-ID').format(value);
        // }

        // document.addEventListener('DOMContentLoaded', function() {
        //     const form = document.querySelector('form');
        //     const priceInput = document.getElementById('price');

        //     // Format once on load for display
        //     if (priceInput && priceInput.value) {
        //         priceInput.value = new Intl.NumberFormat('id-ID').format(priceInput.value.replace(/\D/g, ''));
        //     }

        //     if (form && priceInput) {
        //         form.addEventListener('submit', function() {
        //             priceInput.value = priceInput.value.replace(/\./g, '');
        //         });
        //     }
        // });
    </script> --}}

</x-app-layout>
