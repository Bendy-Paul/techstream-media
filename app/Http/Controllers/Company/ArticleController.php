<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::where('author_id', Auth::user()->id)->latest()->paginate(10);
        return view('company.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::where('type', 'article')->orWhereNull('type')->get();
        $tags       = Tag::all();
        return view('company.articles.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                  => 'required|string|max:255',
            'content'                => 'required|string',
            'is_featured'            => 'boolean',
            'categories'             => 'nullable|array',
            'categories.*'           => 'exists:categories,id',
            'tags'                   => 'nullable|array',
            'tags.*'                 => 'exists:tags,id',
            'featured_image_upload'  => 'nullable|image|max:4096',
        ]);

        $imagePath = null;
        if ($request->hasFile('featured_image_upload')) {
            $imagePath = $request->file('featured_image_upload')->store('uploads/articles', 'public');
        }

        $article = Article::create([
            'author_id'          => Auth::user()->id,
            'title'              => $validated['title'],
            'slug'               => Str::slug($validated['title']) . '-' . uniqid(),
            'content'            => $validated['content'],
            'is_featured'        => $request->boolean('is_featured'),
            'featured_image_url' => $imagePath,
            'status'             => 'draft',
        ]);

        if (!empty($validated['categories'])) {
            $article->categories()->sync($validated['categories']);
        }
        if (!empty($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }

        return redirect()->route('company.articles.index')->with('success', 'Article saved as draft.');
    }

    public function edit(Article $article)
    {
        $this->authorizeArticle($article);

        $categories   = Category::where('type', 'article')->orWhereNull('type')->get();
        $tags         = Tag::all();
        $selectedCats = $article->categories->pluck('id')->toArray();
        $selectedTags = $article->tags->pluck('id')->toArray();

        return view('company.articles.edit', compact('article', 'categories', 'tags', 'selectedCats', 'selectedTags'));
    }

    public function update(Request $request, Article $article)
    {
        $this->authorizeArticle($article);

        $validated = $request->validate([
            'title'                  => 'required|string|max:255',
            'content'                => 'required|string',
            'is_featured'            => 'boolean',
            'categories'             => 'nullable|array',
            'categories.*'           => 'exists:categories,id',
            'tags'                   => 'nullable|array',
            'tags.*'                 => 'exists:tags,id',
            'featured_image_upload'  => 'nullable|image|max:4096',
        ]);

        $imagePath = $article->featured_image_url;
        if ($request->hasFile('featured_image_upload')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('featured_image_upload')->store('uploads/articles', 'public');
        }

        $article->update([
            'title'              => $validated['title'],
            'content'            => $validated['content'],
            'is_featured'        => $request->boolean('is_featured'),
            'featured_image_url' => $imagePath,
        ]);

        $article->categories()->sync($validated['categories'] ?? []);
        $article->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('company.articles.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        $this->authorizeArticle($article);

        if ($article->featured_image_url && Storage::disk('public')->exists($article->featured_image_url)) {
            Storage::disk('public')->delete($article->featured_image_url);
        }

        $article->delete();

        return redirect()->route('company.articles.index')->with('success', 'Article deleted successfully.');
    }

    private function authorizeArticle(Article $article): void
    {
        if ($article->author_id !== Auth::user()->id) {
            abort(403);
        }
    }
}
