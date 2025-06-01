{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Product') }}
        </h2>
    </x-slot>

    <div class="p-6 max-h-[69.5vh] overflow-y-auto">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                    value="{{ old('name') }}" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="price" :value="__('Price')" />
                <x-text-input id="price" class="block mt-1 w-full" type="text" name="price"
                    value="{{ old('price') }}" required oninput="formatPriceInput(this)" />
                <x-input-error :messages="$errors->get('price')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="category_id" :value="__('Category')" />
                <x-select name="category_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}</option>
                    @endforeach
                </x-select>
                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
            </div>

            @php
                $allAttributes = \App\Models\Attribute::all();
            @endphp

            <div x-data="{ selected: {} }" class="mb-6">
                <x-input-label :value="__('Attributes')" class="mb-2" />

                <div class="space-y-4">
                    @foreach ($allAttributes as $attribute)
                        <div class="p-4 border rounded-lg dark:border-gray-700 bg-gray-50 dark:bg-gray-800 shadow-sm">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" :id="'attribute_checkbox_' + {{ $attribute->id }}"
                                    @click="selected[{{ $attribute->id }}] = !selected[{{ $attribute->id }}]"
                                    class="rounded text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                                <label :for="'attribute_checkbox_' + {{ $attribute->id }}"
                                    class="text-sm font-medium text-gray-800 dark:text-gray-100">
                                    {{ $attribute->name }}
                                </label>
                            </div>

                            <template x-if="selected[{{ $attribute->id }}]">
                                <div class="mt-3">
                                    <input type="hidden" name="attributes[]" value="{{ $attribute->id }}">
                                    <input type="text" name="attribute_values[{{ $attribute->id }}]"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                        placeholder="Enter value for {{ $attribute->name }}" />
                                </div>
                            </template>
                        </div>
                    @endforeach
                </div>
            </div>


            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.products.index') }}">
                    <x-secondary-button>Cancel</x-secondary-button>
                </a>
                <x-primary-button type="submit">Save</x-primary-button>
            </div>
        </form>
    </div>
    <script>
        function formatPriceInput(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (!value) {
                input.value = '';
                return;
            }
            input.value = new Intl.NumberFormat('id-ID').format(value);
        }
    </script>
</x-app-layout> --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Product') }}
        </h2>
    </x-slot>

    <div class="p-6 max-h-[69.5vh] overflow-y-auto">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                    value="{{ old('name') }}" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Price -->
            <div class="mb-4">
                <x-input-label for="price" :value="__('Price')" />
                <x-text-input id="price" class="block mt-1 w-full" type="text" name="price"
                    value="{{ old('price') }}" required oninput="formatPriceInput(this)" />
                <x-input-error :messages="$errors->get('price')" class="mt-2" />
            </div>

            <!-- Description -->
            <div class="mb-4">
                <x-input-label for="description" :value="__('Description')" />
                <textarea name="description" id="description"
                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    rows="4">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <!-- Category -->
            <div class="mb-4">
                <x-input-label for="category_id" :value="__('Category')" />
                <x-select name="category_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}</option>
                    @endforeach
                </x-select>
                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
            </div>

            <!-- Attributes -->
            @php
                $allAttributes = \App\Models\Attribute::all();
            @endphp

            <div x-data="{ selected: {} }" class="mb-6">
                <x-input-label :value="__('Attributes')" class="mb-2" />
                <div class="space-y-4">
                    @foreach ($allAttributes as $attribute)
                        <div class="p-4 border rounded-lg dark:border-gray-700 bg-gray-50 dark:bg-gray-800 shadow-sm">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" :id="'attribute_checkbox_' + {{ $attribute->id }}"
                                    @click="selected[{{ $attribute->id }}] = !selected[{{ $attribute->id }}]"
                                    class="rounded text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                                <label :for="'attribute_checkbox_' + {{ $attribute->id }}"
                                    class="text-sm font-medium text-gray-800 dark:text-gray-100">
                                    {{ $attribute->name }}
                                </label>
                            </div>

                            <template x-if="selected[{{ $attribute->id }}]">
                                <div class="mt-3">
                                    <input type="hidden" name="attributes[]" value="{{ $attribute->id }}">
                                    <input type="text" name="attribute_values[{{ $attribute->id }}]"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                        placeholder="Enter value for {{ $attribute->name }}" />
                                </div>
                            </template>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Images -->
            <div class="mb-6">
                <x-input-label for="images" :value="__('Product Images')" />
                <input type="file" id="images" name="images[]" multiple accept="image/*"
                    class="block w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <x-input-error :messages="$errors->get('images')" class="mt-2" />
            </div>

            <!-- Thumbnail Selection (by index or image name) -->
            <div class="mb-6">
                <x-input-label :value="__('Thumbnail Image (Pick from uploaded)')" />
                <input type="number" name="thumbnail" min="0" placeholder="Enter image index (starting from 0)"
                    class="block w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <x-input-error :messages="$errors->get('thumbnail')" class="mt-2" />
                <small class="text-sm text-gray-600 dark:text-gray-400">Example: if you upload 3 images, enter 0 for the first one as thumbnail.</small>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.products.index') }}">
                    <x-secondary-button>Cancel</x-secondary-button>
                </a>
                <x-primary-button type="submit">Save</x-primary-button>
            </div>
        </form>
    </div>

    <script>
        function formatPriceInput(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (!value) {
                input.value = '';
                return;
            }
            input.value = new Intl.NumberFormat('id-ID').format(value);
        }
    </script>
</x-app-layout>
