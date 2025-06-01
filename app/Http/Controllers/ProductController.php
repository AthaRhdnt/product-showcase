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
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $attributes = Attribute::all();
        return view('products.create', compact('categories', 'attributes'));
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
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'images.*'    => 'image|mimes:jpg,jpeg,png|max:2048',
            'thumbnail'   => 'nullable|integer|min:0',
        ]);

        $cleanPrice = (int) str_replace('.', '', $request->price);

        $product = Product::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'price'       => $cleanPrice,
            'category_id' => $request->category_id,
        ]);

        // Sync attributes
        if ($request->filled('attributes')) {
            foreach ($request->attributes as $attributeId) {
                $product->attributes()->attach($attributeId, [
                    'value' => $request->attribute_values[$attributeId] ?? null,
                ]);
            }
        }

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $media = $product->addMedia($image)->toMediaCollection('images');

                if ((int) $request->thumbnail === $index) {
                    $product->update(['thumbnail' => $media->getUrl()]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
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
        $product->load('productAttributes');
        return view('products.edit', compact('product', 'categories', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'         => 'required',
            'price'        => 'required|numeric',
            'category_id'  => 'required|exists:categories,id',
            'image'        => 'nullable|image',
            'attributes'   => 'nullable|array',
            'attributes.*' => 'nullable|string',
        ]);

        $cleanPrice = (int) str_replace('.', '', $request->price);

        $product->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            // 'price'       => $cleanPrice,
            'price'       => $request->price,
            'category_id' => $request->category_id,
        ]);

        if ($request->hasFile('image')) {
            $product->clearMediaCollection('products');
            $product->addMediaFromRequest('image')->toMediaCollection('products');
        }

        // Sync attributes
        $product->productAttributes()->delete();
        if ($request->has('attributes')) {
            foreach ($request->input('attributes') as $attribute_id) {
                $value = $request->input("attribute_values.$attribute_id");

                if ($value) {
                    $product->productAttributes()->create([
                        'attribute_id' => $attribute_id,
                        'value'        => $value,
                    ]);
                }
            }
        }
        // dd($request->all());

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
