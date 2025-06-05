{{-- <x-app-layout>
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
        </form> --}}
{{-- <form method="POST" action="{{ route('admin.products.update', $product) }}">
            @csrf
            @method('PUT')

            <input type="text" name="price" value="12345">
            <button type="submit">Test Submit</button>
        </form> --}}
{{-- </div> --}}

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

{{-- </x-app-layout> --}}

{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="p-6 max-h-[69.5vh] overflow-y-auto">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-4">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                    value="{{ old('name', $product->name) }}" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Price -->
            <div class="mb-4">
                <x-input-label for="price" :value="__('Price')" />
                <x-text-input id="price" class="block mt-1 w-full" type="text" name="price"
                    value="{{ old('price', number_format($product->price, 0, '', '.')) }}" required oninput="formatPriceInput(this)" />
                <x-input-error :messages="$errors->get('price')" class="mt-2" />
            </div>

            <!-- Description -->
            <div class="mb-4">
                <x-input-label for="description" :value="__('Description')" />
                <textarea name="description" id="description"
                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    rows="4">{{ old('description', $product->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <!-- Category -->
            <div class="mb-4">
                <x-input-label for="category_id" :value="__('Category')" />
                <x-select name="category_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}</option>
                    @endforeach
                </x-select>
                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
            </div>

            <!-- Attributes -->
            @php
                $allAttributes = \App\Models\Attribute::all();
                $productAttributes = $product->attributes->pluck('pivot.value', 'id')->toArray();
            @endphp

            <div x-data="{ selected: @json(array_keys($productAttributes)) }" class="mb-6">
                <x-input-label :value="__('Attributes')" class="mb-2" />
                <div class="space-y-4">
                    @foreach ($allAttributes as $attribute)
                        @php
                            $isChecked = array_key_exists($attribute->id, $productAttributes);
                        @endphp
                        <div class="p-4 border rounded-lg dark:border-gray-700 bg-gray-50 dark:bg-gray-800 shadow-sm">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" id="attribute_checkbox_{{ $attribute->id }}"
                                    x-model="selected"
                                    :value="{{ $attribute->id }}"
                                    class="rounded text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                                <label for="attribute_checkbox_{{ $attribute->id }}"
                                    class="text-sm font-medium text-gray-800 dark:text-gray-100">
                                    {{ $attribute->name }}
                                </label>
                            </div>

                            <template x-if="selected.includes({{ $attribute->id }})">
                                <div class="mt-3">
                                    <input type="hidden" name="attributes[]" value="{{ $attribute->id }}">
                                    <input type="text" name="attribute_values[{{ $attribute->id }}]"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                        placeholder="Enter value for {{ $attribute->name }}"
                                        value="{{ old('attribute_values.' . $attribute->id, $productAttributes[$attribute->id] ?? '') }}" />
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
                <small class="text-sm text-gray-600 dark:text-gray-400">Leave blank if you don't want to update the images.</small>
            </div>

            <!-- Thumbnail -->
            <div class="mb-6">
                <x-input-label :value="__('Thumbnail Image (Pick from uploaded)')" />
                <input type="number" name="thumbnail" min="0"
                    value="{{ old('thumbnail', $product->thumbnail) }}"
                    placeholder="Enter image index (starting from 0)"
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

        document.addEventListener('DOMContentLoaded', () => {
            const priceInput = document.getElementById('price');
            if (priceInput) {
                priceInput.value = new Intl.NumberFormat('id-ID').format(
                    priceInput.value.replace(/\D/g, '')
                );
            }
        });
    </script>
</x-app-layout> --}}

{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="p-6 max-h-[69.5vh] overflow-y-auto">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-4">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                    value="{{ old('name', $product->name) }}" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Price -->
            <div class="mb-4">
                <x-input-label for="price" :value="__('Price')" />
                <x-text-input id="price" class="block mt-1 w-full" type="text" name="price"
                    value="{{ old('price', number_format($product->price, 0, '', '.')) }}" required
                    oninput="formatPriceInput(this)" />
                <x-input-error :messages="$errors->get('price')" class="mt-2" />
            </div>

            <!-- Description -->
            <div class="mb-4">
                <x-input-label for="description" :value="__('Description')" />
                <textarea name="description" id="description"
                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    rows="4">{{ old('description', $product->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <!-- Category -->
            <div class="mb-4">
                <x-input-label for="category_id" :value="__('Category')" />
                <x-select name="category_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}</option>
                    @endforeach
                </x-select>
                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
            </div>

            <!-- Attributes -->
            @php
                $allAttributes = \App\Models\Attribute::all();
                $productAttributes = $product->attributes->pluck('pivot.value', 'id')->toArray();
            @endphp

            <div x-data="{ selected: @json(array_keys($productAttributes)) }" class="mb-6">
                <x-input-label :value="__('Attributes')" class="mb-2" />
                <div class="space-y-4">
                    @foreach ($allAttributes as $attribute)
                        <div class="p-4 border rounded-lg dark:border-gray-700 bg-gray-50 dark:bg-gray-800 shadow-sm">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" :id="'attribute_checkbox_' + {{ $attribute->id }}"
                                    @click="selected.includes({{ $attribute->id }}) ? selected.splice(selected.indexOf({{ $attribute->id }}), 1) : selected.push({{ $attribute->id }})"
                                    :checked="selected.includes({{ $attribute->id }})"
                                    class="rounded text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                                <label :for="'attribute_checkbox_' + {{ $attribute->id }}"
                                    class="text-sm font-medium text-gray-800 dark:text-gray-100">
                                    {{ $attribute->name }}
                                </label>
                            </div>

                            <template x-if="selected.includes({{ $attribute->id }})">
                                <div class="mt-3">
                                    <input type="hidden" name="attributes[]" value="{{ $attribute->id }}">
                                    <input type="text" name="attribute_values[{{ $attribute->id }}]"
                                        value="{{ old("attribute_values.$attribute->id", $productAttributes[$attribute->id] ?? '') }}"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                        placeholder="Enter value for {{ $attribute->name }}" />
                                </div>
                            </template>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- New Images Upload -->
            <div class="mb-6">
                <x-input-label for="images" :value="__('Upload New Images')" />
                <input type="file" id="images" name="images[]" multiple accept="image/*"
                    class="block w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <x-input-error :messages="$errors->get('images')" class="mt-2" />
            </div>

            <!-- Existing Images Preview -->
            <div class="mb-6">
                <x-input-label :value="__('Existing Images')" />
                <div id="existing-images" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    @foreach ($product->images ?? [] as $index => $image)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $image->path) }}"
                                class="w-full h-32 object-cover rounded border @if ($image->is_thumbnail) border-indigo-500 @endif">
                            <input type="radio" name="thumbnail" value="{{ $image->id }}"
                                class="absolute top-2 left-2 w-5 h-5" @checked(old('thumbnail', $product->images->where('is_thumbnail', true)->first()->id ?? null) == $image->id)>
                        </div>
                    @endforeach
                </div>

                <!-- Previews of new uploaded images -->
                <div id="image-preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4"></div>
            </div>

            <!-- Hidden Input for New Thumbnail Index (Handled via JS) -->
            <input type="hidden" name="new_thumbnail_index" id="new_thumbnail_index">

            <!-- Buttons -->
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.products.index') }}">
                    <x-secondary-button>Cancel</x-secondary-button>
                </a>
                <x-primary-button type="submit">Update</x-primary-button>
            </div>
        </form> --}}
{{-- <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- Product fields -->
            <input type="text" name="name" value="{{ $product->name }}" required>
            <input type="text" name="slug" value="{{ $product->slug }}" required>
            <textarea name="description">{{ $product->description }}</textarea>
            <input type="number" name="price" value="{{ $product->price }}" required>
            <select name="category_id" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}</option>
                @endforeach
            </select>

            <!-- Existing images -->
            <div>
                @foreach ($product->getMedia('images') as $media)
                    <div class="existing-image">
                        <img src="{{ $media->getUrl('thumb') }}" style="width:100px; height:100px;">
                        <input type="radio" name="existing_thumbnail_id" value="{{ $media->id }}"
                            {{ $product->thumbnail === $media->getUrl('thumb') ? 'checked' : '' }}>
                    </div>
                @endforeach
            </div>

            <!-- New image uploads -->
            <input type="file" name="images[]" multiple accept="image/*" id="image-input">

            <!-- New image previews and thumbnail selection -->
            <div id="image-preview-container"></div>
            <input type="hidden" name="thumbnail_index" id="thumbnail-index">

            <button type="submit">Update Product</button>
        </form> --}}
{{-- </div> --}}

{{-- <script>
        function formatPriceInput(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (!value) {
                input.value = '';
                return;
            }
            input.value = new Intl.NumberFormat('id-ID').format(value);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const imagesInput = document.getElementById('images');
            const previewContainer = document.getElementById('image-preview-container');
            const newThumbnailInput = document.getElementById('new_thumbnail_index');

            imagesInput.addEventListener('change', function() {
                previewContainer.innerHTML = '';
                const files = Array.from(this.files);

                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.classList.add('relative');

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('w-full', 'h-32', 'object-cover', 'rounded');

                        const radio = document.createElement('input');
                        radio.type = 'radio';
                        radio.name = 'new_thumbnail_selector';
                        radio.value = index;
                        radio.classList.add('absolute', 'top-2', 'left-2', 'w-5', 'h-5');
                        radio.addEventListener('change', function() {
                            newThumbnailInput.value = this.value;
                        });

                        div.appendChild(img);
                        div.appendChild(radio);
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            });
        });
    </script> --}}
{{-- <script>
        document.getElementById('image-input').addEventListener('change', function() {
            const previewContainer = document.getElementById('image-preview-container');
            previewContainer.innerHTML = '';
            const files = Array.from(this.files);

            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.classList.add('image-preview');

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100px';
                    img.style.height = '100px';

                    const radio = document.createElement('input');
                    radio.type = 'radio';
                    radio.name = 'thumbnail_selector';
                    radio.value = index;
                    radio.addEventListener('change', function() {
                        document.getElementById('thumbnail-index').value = this.value;
                    });

                    div.appendChild(img);
                    div.appendChild(radio);
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
</x-app-layout> --}}

{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="p-6 max-h-[69.5vh] overflow-y-auto">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @php
                $allAttributes = \App\Models\Attribute::all();
                $productAttributes = $product->attributes->pluck('pivot.value', 'id')->toArray();
                $existingImages = $product->images;
                $existingImagesFormatted = $existingImages->map(function ($img) {
                    return [
                        'url' => asset('storage/' . $img->path),
                        'name' => basename($img->path),
                        'id' => $img->id,
                    ];
                });
                $existingThumbnailId = $product->images->firstWhere('is_thumbnail', true)?->id;
            @endphp
            <x-products.form :product="$product" :categories="$categories" :all-attributes="$allAttributes" :existing-images="$existingImagesFormatted"
                :thumbnail-id="$existingThumbnailId" />
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.products.index') }}"><x-secondary-button>Cancel</x-secondary-button></a>
                <x-primary-button type="submit">Update</x-primary-button>
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

{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="p-6 max-h-[69.5vh] overflow-y-auto">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @php
                $allAttributes = \App\Models\Attribute::all();
                $productAttributes = $product->attributes->pluck('pivot.value', 'id')->toArray();
                $existingImages = $product->images;
                $existingImagesFormatted = $existingImages->map(function ($img) {
                    return [
                        'url' => asset($img->path),
                        'name' => basename($img->path),
                        'id' => $img->id,
                    ];
                });
                $existingThumbnailId = $product->images->firstWhere('is_thumbnail', true)?->id;
            @endphp

            <x-products.form
                :product="$product"
                :categories="$categories"
                :all-attributes="$allAttributes"
                :existing-images="$existingImagesFormatted"
                :thumbnail-id="$existingThumbnailId"
            />

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.products.index') }}">
                    <x-secondary-button>Cancel</x-secondary-button>
                </a>
                <x-primary-button type="submit">Update</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout> --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="p-6 max-h-[69.5vh] overflow-y-auto">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @php
                $allAttributes = \App\Models\Attribute::all();
                $existingImages = $product->images;
                $existingImagesFormatted = $existingImages->map(function ($img) {
                    return [
                        'url' => asset($img->path),
                        'name' => basename($img->path),
                        'id' => $img->id,
                    ];
                });
                $existingThumbnailId = $product->images->firstWhere('is_thumbnail', true)?->id;
            @endphp

            <x-products.form
                :product="$product"
                :categories="$categories"
                :all-attributes="$allAttributes"
                :existing-images="$existingImagesFormatted"
                :thumbnail-id="$existingThumbnailId"
            />

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.products.index') }}">
                    <x-secondary-button>Cancel</x-secondary-button>
                </a>
                <x-primary-button type="submit">Update</x-primary-button>
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
