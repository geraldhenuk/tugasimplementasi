<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function show()
    {
        // Jika pengguna sudah pernah memilih minimal 3 kategori, langsung lempar ke Home
        if (Auth::user()->preferences()->count() >= 3) {
            return redirect()->route('home');
        }

        $categories = Category::all();
        return view('onboarding', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'categories'   => 'required|array|min:3',
            'categories.*' => 'exists:categories,id',
        ], [
            'categories.required' => 'Silakan pilih kategori minat Anda terlebih dahulu.',
            'categories.min'      => 'Pilih minimal 3 kategori agar AI dapat mengenali minat Anda.',
        ]);

        $userId = Auth::id();

        // Bersihkan preferensi lama (jika ada) lalu simpan pilihan baru
        UserPreference::where('user_id', $userId)->delete();

        foreach ($request->categories as $categoryId) {
            UserPreference::create([
                'user_id'     => $userId,
                'category_id' => $categoryId,
            ]);
        }

        return redirect()->route('home')->with('success', 'Preferensi minat berhasil disimpan!');
    }
}