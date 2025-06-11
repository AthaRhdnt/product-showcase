<?php
namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search filter
        if ($search = $request->input('search')) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('slug', 'like', '%' . $search . '%');
        }

        // Sorting
        $sort      = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        $query->orderBy($sort, $direction);

        // Entries per page
        $perPage = $request->input('entries', 5);

        $products = $query->paginate($perPage)->appends($request->all());

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Product $product)
    {
        $categories = Category::all();
        $attributes = Attribute::all();
        $product->load('productAttributes');
        return view('products.create', compact('product', 'categories', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'price'              => 'required|string',
            'description'        => 'nullable|string',
            'category_id'        => 'required|exists:categories,id',
            'attributes'         => 'array',
            'attributes.*'       => 'exists:attributes,id',
            'attribute_values'   => 'array',
            'attribute_values.*' => 'string',
            'images'             => 'nullable|array',
            'images.*'           => 'nullable|image|max:2048',
            'thumbnail_index'    => 'nullable|integer',
        ]);

        // Convert price from formatted string (e.g. "12.000") to integer (e.g. 12000)
        $validated['price'] = (int) str_replace('.', '', $validated['price']);

        // Store product
        $product = Product::create([
            'name'        => $validated['name'],
            'slug'        => $this->generateUniqueSlug($validated['name']),
            'price'       => $validated['price'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'],
        ]);

        // Attach attributes
        if (isset($validated['attributes']) && isset($validated['attribute_values'])) {
            $attributes = collect($validated['attributes'])->mapWithKeys(function ($attributeId) use ($validated) {
                return [$attributeId => ['value' => $validated['attribute_values'][$attributeId] ?? '']];
            })->toArray();

            $product->attributes()->attach($attributes);
        }

        // Store images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();

                // Save to storage/app/public/products
                $path = $image->storeAs('products', $filename, 'public');

                // Save to DB as: storage/products/filename.jpg
                $product->images()->create([
                    'path'         => 'storage/' . $path,
                    'is_thumbnail' => (int) $request->input('thumbnail_index') === $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $attributes = Attribute::all();
        $product->load(['productAttributes', 'images']);
        return view('products.edit', compact('product', 'categories', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'price'              => 'required|string',
            'description'        => 'nullable|string',
            'category_id'        => 'required|exists:categories,id',
            'attributes'         => 'array',
            'attributes.*'       => 'exists:attributes,id',
            'attribute_values'   => 'array',
            'attribute_values.*' => 'string',
            'images'             => 'nullable|array',
            'images.*'           => 'nullable|image|max:2048',
            'thumbnail_index'    => 'nullable|integer',
        ]);

        $validated['price'] = (int) str_replace('.', '', $validated['price']);

        $updateData = [
            'name'        => $validated['name'],
            'price'       => $validated['price'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'],
        ];

        // Only update slug if name has changed
        if ($product->name !== $validated['name']) {
            $slug               = $this->generateUniqueSlug($validated['name'], $product->id); // pass ID to exclude current product
            $updateData['slug'] = $slug;
        }

        $product->update($updateData);

        // Sync attributes
        if (isset($validated['attributes']) && isset($validated['attribute_values'])) {
            $attributes = collect($validated['attributes'])->mapWithKeys(function ($attributeId) use ($validated) {
                return [$attributeId => ['value' => $validated['attribute_values'][$attributeId] ?? '']];
            })->toArray();

            $product->attributes()->sync($attributes);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * Generate a unique slug by appending -2, -3, etc. if needed.
     */
    private function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug     = $baseSlug;
        $counter  = 2;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

}
