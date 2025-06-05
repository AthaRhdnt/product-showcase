@props([
    'label',
    'name',
    'type' => 'text',
    'value' => '',
    'required' => false,
    'autofocus' => false
])

<div class="mb-4">
    <x-input-label :for="$name" :value="$label" />
    <x-text-input
        :id="$name"
        :name="$name"
        :type="$type"
        class="block mt-1 w-full"
        :value="old($name, $value)"
        :required="$required"
        :autofocus="$autofocus"
    />
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
