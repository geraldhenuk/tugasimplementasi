<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Interaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('stock', '>', 0);

        // Fitur Search Bar
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter Kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Fitur Sorting Harga
        if ($request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(9)->withQueryString();
        $categories = Category::where('type', 'product')->get();

        return view('shop.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        // Rekam Implicit Feedback ('click') otomatis untuk AI Python
        Interaction::create([
            'user_id'          => Auth::id(),
            'item_id'          => $product->id,
            'item_type'        => 'product',
            'interaction_type' => 'click',
        ]);

        // Cek apakah user sudah pernah me-like produk ini
        $isLiked = Interaction::where('user_id', Auth::id())
            ->where('item_id', $product->id)
            ->where('item_type', 'product')
            ->where('interaction_type', 'like')
            ->exists();

        return view('shop.show', compact('product', 'isLiked'));
    }
}