<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Tag;
use App\Models\Category;

class Articlecontroller extends Controller
{
    //
    public function index()
    {
        $articles = Article::all();
        $categories = Category::where('type', 'article')->get();
        $tags = Tag::limit(7)->get();

        $featured = Article::where('status', 'published')
            ->where('is_featured', 1)
            ->latest('published_at')
            ->first();

        return view('public.news', [
            'articles' => $articles,
            'categories' => $categories,
            'tags' => $tags,
            'featured' => $featured
        ]);
    }
}
