@props([
    'href' => route('public.index'),
    'label' => '← Back',
    'class' => ''
])

<a href="{{ $href }}"
    class="inline-block mb-6 text-sm px-5 py-2 rounded bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-600 transition {{ $class }}">
    {{ $label }}
</a>
