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
        $perPage = $request->input('entries', 10);

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
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name'         => 'required',
    //         'price'        => 'required|numeric',
    //         'category_id'  => 'required|exists:categories,id',
    //         'image'        => 'nullable|image',
    //         'attributes'   => 'nullable|array',
    //         'attributes.*' => 'nullable|string',
    //     ]);

    //     $cleanPrice = (int) str_replace('.', '', $request->price);

    //     $product = Product::create([
    //         'name'        => $request->name,
    //         'slug'        => Str::slug($request->name),
    //         'description' => $request->description,
    //         'price'       => $cleanPrice,
    //         'category_id' => $request->category_id,
    //     ]);

    //     // Image upload with Spatie
    //     if ($request->hasFile('image')) {
    //         $product->addMediaFromRequest('image')->toMediaCollection('products');
    //     }

    //     // Attributes
    //     if ($request->has('attributes')) {
    //         foreach ($request->input('attributes') as $attribute_id) {
    //             $value = $request->input("attribute_values.$attribute_id");

    //             if ($value) {
    //                 $product->productAttributes()->create([
    //                     'attribute_id' => $attribute_id,
    //                     'value'        => $value,
    //                 ]);
    //             }
    //         }
    //     }

    //     return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    // }
    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'name'            => 'required|string|max:255',
    //         'slug'            => 'required|string|unique:products,slug',
    //         'description'     => 'nullable|string',
    //         'price'           => 'required',
    //         'category_id'     => 'required|exists:categories,id',
    //         'images.*'        => 'image|max:2048',
    //         'thumbnail_index' => 'nullable|integer',
    //     ]);

    //     $cleanPrice = (int) str_replace('.', '', $request->price);

    //     $product = Product::create([
    //         'name'        => $request->name,
    //         'slug'        => Str::slug($request->name),
    //         'description' => $request->description,
    //         'price'       => $cleanPrice,
    //         'category_id' => $request->category_id,
    //     ]);

    //     // Sync attributes
    //     if ($request->filled('attributes')) {
    //         foreach ($request->attributes as $attributeId) {
    //             $product->attributes()->attach($attributeId, [
    //                 'value' => $request->attribute_values[$attributeId] ?? null,
    //             ]);
    //         }
    //     }

    //     // Handle images
    //     if ($request->hasFile('images')) {
    //         foreach ($request->file('images') as $index => $image) {
    //             $filename = time() . '_' . $image->getClientOriginalName();
    //             $path     = $image->storeAs('uploads/products', $filename, 'public');

    //             ProductImage::create([
    //                 'product_id'   => $product->id,
    //                 'filename'     => $path,
    //                 'is_thumbnail' => $index == $request->input('thumbnail_index'),
    //             ]);

    //             if ($index == $request->input('thumbnail_index')) {
    //                 $product->update(['thumbnail' => $path]);
    //             }
    //         }
    //     }

    //     return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    // }
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
            'slug'        => Str::slug($validated['name']),
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
            \Log::info('Images detected in request');
            foreach ($request->file('images') as $index => $image) {
                \Log::info("Image $index original name: " . $image->getClientOriginalName());
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

        // Check if name has changed
        $nameChanged = $product->name !== $request->name;

        // If changed, generate a new slug and validate it
        if ($nameChanged) {
            $slug = Str::slug($request->name);

            $request->validate([
                'slug' => ['unique:products,slug,' . $product->id],
            ]);

            $validated['slug'] = $slug;
        }

        $updateData = [
            'name'        => $validated['name'],
            'price'       => $validated['price'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'],
        ];

        if ($nameChanged) {
            $updateData['slug'] = $validated['slug'];
        }

        // Update product
        // $product->update([
        //     'name'        => $validated['name'],
        //     'slug'        => Str::slug($request->name),
        //     'price'       => $validated['price'],
        //     'description' => $validated['description'] ?? null,
        //     'category_id' => $validated['category_id'],
        // ]);
        $product->update($updateData);

        // Sync attributes
        if (isset($validated['attributes']) && isset($validated['attribute_values'])) {
            $attributes = collect($validated['attributes'])->mapWithKeys(function ($attributeId) use ($validated) {
                return [$attributeId => ['value' => $validated['attribute_values'][$attributeId] ?? '']];
            })->toArray();

            $product->attributes()->sync($attributes);
        }

        // // Handle new uploaded images
        // $uploadedImages = $request->file('images', []);
        // $thumbnailIndex = (int) $request->input('thumbnail_index');

        // foreach ($uploadedImages as $index => $imageFile) {
        //     $path        = $imageFile->store('products', 'public');
        //     $isThumbnail = $index === $thumbnailIndex;

        //     $product->images()->create([
        //         'path'         => $path,
        //         'is_thumbnail' => $isThumbnail,
        //     ]);
        // }

        // // If thumbnail is from existing images
        // if ($request->filled('existing_thumbnail_id')) {
        //     $product->images()->update(['is_thumbnail' => false]);
        //     $product->images()
        //         ->where('id', $request->input('existing_thumbnail_id'))
        //         ->update(['is_thumbnail' => true]);
        // }

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
}
