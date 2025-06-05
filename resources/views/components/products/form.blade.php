{{-- @props(['product' => null, 'categories', 'allAttributes'])

@php
    $productAttributes = $product?->attributes->pluck('pivot.value', 'id')->toArray() ?? [];
@endphp

<!-- Name -->
<div class="mb-4">
    <x-input-label for="name" :value="__('Name')" />
    <x-text-input id="name" name="name" type="text" class="block mt-1 w-full"
        :value="old('name', $product->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<!-- Price -->
<div class="mb-4">
    <x-input-label for="price" :value="__('Price')" />
    <x-text-input id="price" name="price" type="text" class="block mt-1 w-full"
        :value="old('price', isset($product) ? number_format($product->price, 0, '', '.') : '')"
        required oninput="formatPriceInput(this)" />
    <x-input-error :messages="$errors->get('price')" class="mt-2" />
</div>

<!-- Description -->
<div class="mb-4">
    <x-input-label for="description" :value="__('Description')" />
    <textarea id="description" name="description" rows="4"
        class="block mt-1 w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        {{ old('description', $product->description ?? '') }}
    </textarea>
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
<div x-data="{ selected: {{ Js::from(array_keys($productAttributes)) }} }" class="mb-6">
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
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Enter value for {{ $attribute->name }}"
                            value="{{ old('attribute_values.' . $attribute->id, $productAttributes[$attribute->id] ?? '') }}">
                    </div>
                </template>
            </div>
        @endforeach
    </div>
</div>

<!-- Images -->
<div
    x-data="imageUploader({{ json_encode($existingImages ?? []) }}, {{ $thumbnailId ?? 'null' }})"
    class="mb-6"
>
    <x-input-label for="images" :value="__('Upload Images')" />
    <input
        type="file"
        name="images[]"
        id="images"
        multiple
        @change="handleFiles($event)"
        accept="image/*"
        class="block w-full"
    />

    <!-- Hidden input for selected thumbnail -->
    <input type="hidden" name="thumbnail_index" :value="thumbnailIndex" />

    <!-- Hidden inputs for keeping existing images -->
    <template x-for="image in imagesPreview" :key="image.index">
        <template x-if="image.isExisting && !image.markedForDeletion">
            <input type="hidden" name="existing_images[]" :value="image.id" />
        </template>
    </template>

    <template x-if="imagesPreview.length > 0">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
            <template x-for="(image, index) in imagesPreview" :key="index">
                <div class="relative border rounded overflow-hidden group">
                    <img :src="image.url" class="w-full h-32 object-cover" />

                    <!-- Radio for thumbnail -->
                    <div class="absolute top-1 right-1 bg-white bg-opacity-70 rounded-full p-1">
                        <input
                            type="radio"
                            :value="index"
                            x-model="thumbnailIndex"
                            class="form-radio text-blue-500"
                        />
                    </div>

                    <!-- Remove button -->
                    <button type="button"
                        @click="removeImage(index)"
                        class="absolute top-1 left-1 bg-red-600 text-white text-xs px-2 py-1 rounded"
                    >✕</button>

                    <!-- Image name overlay -->
                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-30 text-white text-xs text-center py-1">
                        <span x-text="image.name"></span>
                    </div>
                </div>
            </template>
        </div>
    </template>
</div>

<script>
    function imageUploader(existingImages = [], thumbnailId = null) {
        return {
            imagesPreview: existingImages.map((img, index) => ({
                url: img.url,
                name: img.name,
                id: img.id,
                isExisting: true,
                markedForDeletion: false,
                index: index,
            })),
            thumbnailIndex: thumbnailId !== null
                ? existingImages.findIndex(img => img.id === thumbnailId)
                : null,

            handleFiles(event) {
                const files = [...event.target.files];
                files.forEach((file) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imagesPreview.push({
                            url: e.target.result,
                            name: file.name,
                            file: file,
                            isExisting: false,
                            index: this.imagesPreview.length,
                        });
                    };
                    reader.readAsDataURL(file);
                });
            },

            removeImage(index) {
                const image = this.imagesPreview[index];
                if (image.isExisting) {
                    this.imagesPreview[index].markedForDeletion = true;
                } else {
                    this.imagesPreview.splice(index, 1);
                }

                // Recalculate indexes
                this.imagesPreview = this.imagesPreview.filter(img => !img.markedForDeletion).map((img, i) => {
                    img.index = i;
                    return img;
                });

                // Adjust thumbnail index if needed
                if (this.thumbnailIndex === index) {
                    this.thumbnailIndex = null;
                } else if (this.thumbnailIndex > index) {
                    this.thumbnailIndex--;
                }
            }
        }
    }
</script> --}}
@props(['product' => null, 'categories', 'allAttributes', 'existingImages' => [], 'thumbnailId' => null])

@php
    $productAttributes = $product?->attributes->pluck('pivot.value', 'id')->toArray() ?? [];
@endphp

<!-- Name -->
<div class="mb-4">
    <x-input-label for="name" :value="__('Name')" />
    <x-text-input id="name" name="name" type="text" class="block mt-1 w-full"
        :value="old('name', $product->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<!-- Price -->
<div class="mb-4">
    <x-input-label for="price" :value="__('Price')" />
    <x-text-input id="price" name="price" type="text" class="block mt-1 w-full"
        :value="old('price', isset($product) ? number_format($product->price, 0, '', '.') : '')"
        required oninput="formatPriceInput(this)" />
    <x-input-error :messages="$errors->get('price')" class="mt-2" />
</div>

<!-- Description -->
<div class="mb-4">
    <x-input-label for="description" :value="__('Description')" />
    <textarea id="description" name="description" rows="4"
        class="block mt-1 w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $product->description ?? '') }}</textarea>
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
<div x-data="{ selected: {{ Js::from(array_keys($productAttributes)) }} }" class="mb-6">
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
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Enter value for {{ $attribute->name }}"
                            value="{{ old('attribute_values.' . $attribute->id, $productAttributes[$attribute->id] ?? '') }}">
                    </div>
                </template>
            </div>
        @endforeach
    </div>
</div>

<!-- Images -->
<div
    x-data="imageUploader({{ json_encode($existingImages) }}, {{ $thumbnailId ?? 'null' }})"
    class="mb-6"
>
    <x-input-label for="images" :value="__('Upload Images')" />
    <input
        type="file"
        name="images[]"
        id="images"
        multiple
        @change="handleFiles($event)"
        accept="image/*"
        class="block w-full"
    />

    <!-- Hidden input for selected thumbnail -->
    <input type="hidden" name="thumbnail_index" :value="thumbnailIndex" />

    <!-- Hidden inputs for keeping existing images -->
    <template x-for="image in imagesPreview" :key="image.index">
        <template x-if="image.isExisting && !image.markedForDeletion">
            <input type="hidden" name="existing_images[]" :value="image.id" />
        </template>
    </template>

    <template x-if="imagesPreview.length > 0">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
            <template x-for="(image, index) in imagesPreview" :key="index">
                <div class="relative border rounded overflow-hidden group">
                    <img :src="image.url" class="w-full h-32 object-cover" />

                    <!-- Radio for thumbnail -->
                    <div class="absolute top-1 right-1 bg-white bg-opacity-70 rounded-full p-1">
                        <input
                            type="radio"
                            :value="index"
                            x-model="thumbnailIndex"
                            class="form-radio text-blue-500"
                        />
                    </div>

                    <!-- Remove button -->
                    <button type="button"
                        @click="removeImage(index)"
                        class="absolute top-1 left-1 bg-red-600 text-white text-xs px-2 py-1 rounded"
                    >✕</button>

                    <!-- Image name overlay -->
                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-30 text-white text-xs text-center py-1">
                        <span x-text="image.name"></span>
                    </div>
                </div>
            </template>
        </div>
    </template>
</div>

<script>
    function imageUploader(existingImages = [], thumbnailId = null) {
        return {
            imagesPreview: existingImages.map((img, index) => ({
                url: img.url,
                name: img.name,
                id: img.id,
                isExisting: true,
                markedForDeletion: false,
                index: index,
            })),
            thumbnailIndex: thumbnailId !== null
                ? existingImages.findIndex(img => img.id === thumbnailId)
                : null,

            handleFiles(event) {
                const files = [...event.target.files];
                files.forEach((file) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imagesPreview.push({
                            url: e.target.result,
                            name: file.name,
                            file: file,
                            isExisting: false,
                            index: this.imagesPreview.length,
                        });
                    };
                    reader.readAsDataURL(file);
                });
            },

            removeImage(index) {
                const image = this.imagesPreview[index];
                if (image.isExisting) {
                    this.imagesPreview[index].markedForDeletion = true;
                } else {
                    this.imagesPreview.splice(index, 1);
                }

                // Recalculate indexes
                this.imagesPreview = this.imagesPreview.filter(img => !img.markedForDeletion).map((img, i) => {
                    img.index = i;
                    return img;
                });

                // Adjust thumbnail index if needed
                if (this.thumbnailIndex === index) {
                    this.thumbnailIndex = null;
                } else if (this.thumbnailIndex > index) {
                    this.thumbnailIndex--;
                }
            }
        }
    }
</script>
