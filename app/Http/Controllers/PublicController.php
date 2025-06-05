<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // 🔍 Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 📂 Category Filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // 💰 Price Range
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // 🔃 Sorting
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name');
                break;
        }

        $products   = $query->paginate(9)->withQueryString();
        $categories = Category::all();

        return view('public.index', compact('products', 'categories'));
    }

    public function compare()
    {
        session(['compare_back_url' => url()->previous()]);

        $compareIds = session('compare', []);

        $compareProducts = \App\Models\Product::with(['category', 'attributes'])
            ->whereIn('id', $compareIds)
            ->get();

        // Get all attributes used in these products
        $allAttributeIds = $compareProducts
            ->flatMap(fn($product) => $product->attributes->pluck('id'))
            ->unique();

        $allAttributes = \App\Models\Attribute::whereIn('id', $allAttributeIds)->get();

        return view('public.compare', compact('compareProducts', 'allAttributes'));
    }

    // public function toggleCompare(Request $request, $id)
    // {
    //     $compare = session()->get('compare', []);

    //     if (in_array($id, $compare)) {
    //         // Remove from compare
    //         $compare = array_filter($compare, fn($pid) => $pid != $id);
    //         session()->put('compare', array_values($compare));
    //         return redirect()->back()->with('success', 'Product removed from comparison list.');
    //     } else {
    //         // Add to compare
    //         $compare[] = $id;
    //         session()->put('compare', array_unique($compare));
    //         return redirect()->back()->with('success', 'Product added to comparison list.');
    //     }
    // }
    // public function toggleCompare(Request $request, $id)
    // {
    //     $compare = session()->get('compare', []);

    //     if (in_array($id, $compare)) {
    //         $compare = array_filter($compare, fn($pid) => $pid != $id);
    //         session()->put('compare', array_values($compare));

    //         if ($request->expectsJson()) {
    //             return response()->json(['status' => 'removed']);
    //         }

    //         return redirect()->back()->with('success', 'Product removed from comparison list.');
    //     } else {
    //         $compare[] = $id;
    //         session()->put('compare', array_unique($compare));

    //         if ($request->expectsJson()) {
    //             return response()->json(['status' => 'added']);
    //         }

    //         return redirect()->back()->with('success', 'Product added to comparison list.');
    //     }
    // }
    public function toggleCompare(Request $request, $id)
    {
        $compare = session()->get('compare', []);

        if (in_array($id, $compare)) {
            $compare = array_filter($compare, fn($pid) => $pid != $id);
            session()->put('compare', array_values($compare));

            if ($request->expectsJson()) {
                return response()->json(['status' => 'removed']);
            }

            return redirect()->back()->with('success', 'Product removed from comparison list.');
        } else {
            $compare[] = $id;
            session()->put('compare', array_unique($compare));

            if ($request->expectsJson()) {
                return response()->json(['status' => 'added']);
            }

            return redirect()->back()->with('success', 'Product added to comparison list.');
        }
    }

    public function show(Product $product)
    {
        $product->load(['category', 'attributes']);
        return view('public.show', compact('product'));
    }

}
