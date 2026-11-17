<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Interaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with('category');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $articles = $query->latest()->paginate(6)->withQueryString();
        $categories = Category::where('type', 'article')->get();

        return view('education.index', compact('articles', 'categories'));
    }

    public function show(Article $article)
    {
        // Rekam Implicit Feedback ('click') otomatis untuk AI Python
        Interaction::create([
            'user_id'          => Auth::id(),
            'item_id'          => $article->id,
            'item_type'        => 'article',
            'interaction_type' => 'click',
        ]);

        $isLiked = Interaction::where('user_id', Auth::id())
            ->where('item_id', $article->id)
            ->where('item_type', 'article')
            ->where('interaction_type', 'like')
            ->exists();

        return view('education.show', compact('article', 'isLiked'));
    }
}