<form method="GET" action="{{ route('public.index') }}" class="space-y-4">

    <!-- Search Bar -->
    <div>
        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search products..."
            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
    </div>

    <!-- Category Filter -->
    <div>
        <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
        <select name="category" id="category"
            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">All Categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Price Range Filter -->
    <div>
        <label for="price_min" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price
            Range</label>
        <div class="flex space-x-2 mt-1">
            <input type="number" name="price_min" id="price_min" placeholder="Min" value="{{ request('price_min') }}"
                class="w-1/2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <input type="number" name="price_max" id="price_max" placeholder="Max" value="{{ request('price_max') }}"
                class="w-1/2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
    </div>

    <!-- Sort Dropdown -->
    <div>
        <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sort By</label>
        <select name="sort" id="sort"
            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">Default</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High
            </option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to
                Low</option>
            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
        </select>
    </div>

    <!-- Submit Button -->
    <div class="flex flex-col space-y-2">
        <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">Apply
            Filters</button>

        <a href="{{ route('public.index') }}"
            class="text-center w-full bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-white py-2 px-4 rounded-md hover:bg-gray-300 dark:hover:bg-gray-700">
            Clear Filters
        </a>
    </div>
</form>
