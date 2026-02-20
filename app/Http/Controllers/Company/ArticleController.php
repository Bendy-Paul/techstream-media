<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::where('author_id', auth()->id())->latest()->paginate(10);
        return view('company.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('company.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            // add more validation
        ]);

        $validated['author_id'] = auth()->id();
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title'] . '-' . uniqid());
        $validated['status'] = 'draft';

        Article::create($validated);

        return redirect()->route('company.articles.index')->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        if ($article->author_id !== auth()->id()) {
            abort(403);
        }
        $categories = Category::all();
        return view('company.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        if ($article->author_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $article->update($validated);
        return redirect()->route('company.articles.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        if ($article->author_id !== auth()->id()) {
            abort(403);
        }
        $article->delete();
        return redirect()->route('company.articles.index')->with('success', 'Article deleted successfully.');
    }
}
