<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Article;
use App\Models\Interaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Wajib selesaikan Onboarding minimal 3 kategori
        if ($user->preferences()->count() < 3) {
            return redirect()->route('onboarding.show');
        }

        $products = collect();
        $articles = collect();
        $aiStatus = 'fallback';

        try {
            // Panggil server Python (main.py) di port 8001 dengan timeout 3 detik
            $response = Http::timeout(3)->get("http://127.0.0.1:8001/api/recommend/{$user->id}");

            if ($response->successful()) {
                $data = $response->json();
                
                if (!empty($data['product_ids'])) {
                    $products = Product::with('category')->whereIn('id', $data['product_ids'])->where('stock', '>', 0)->get();
                }
                if (!empty($data['article_ids'])) {
                    $articles = Article::with('category')->whereIn('id', $data['article_ids'])->get();
                }
                $aiStatus = 'active';
            }
        } catch (\Exception $e) {
            // Jika Python mati/timeout, sistem otomatis menggunakan fallback di bawah
        }

        // FALLBACK SYSTEM
        if ($products->isEmpty()) {
            $products = Product::with('category')->where('stock', '>', 0)->latest()->take(6)->get();
        }
        if ($articles->isEmpty()) {
            $articles = Article::with('category')->inRandomOrder()->take(4)->get();
        }

        return view('home', compact('products', 'articles', 'aiStatus'));
    }

    public function recordInteraction(Request $request)
    {
        $validated = $request->validate([
            'item_id'          => 'required|integer',
            'item_type'        => 'required|in:product,article',
            'interaction_type' => 'required|in:click,like,rating',
            'rating_value'     => 'nullable|integer|min:1|max:5',
        ]);

        Interaction::create([
            'user_id'          => Auth::id(),
            'item_id'          => $validated['item_id'],
            'item_type'        => $validated['item_type'],
            'interaction_type' => $validated['interaction_type'],
            'rating_value'     => $validated['rating_value'] ?? null,
        ]);

        return back()->with('success', 'Terima kasih! Preferensi Anda telah dicatat oleh AI.');
    }
}