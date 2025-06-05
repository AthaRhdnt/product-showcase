<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Attribute') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <form method="POST" action="{{ route('admin.attributes.store') }}">
            @csrf

            <x-form.input-group label="Name" name="name" required />
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.attributes.index') }}">
                    <x-secondary-button>Cancel</x-secondary-button>
                </a>
                <x-primary-button type="submit">Save</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>