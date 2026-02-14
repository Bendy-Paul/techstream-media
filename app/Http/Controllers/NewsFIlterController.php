<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;

class NewsFIlterController extends Controller
{
    public function index(Request $request)
    {

    // 1. Get parameters with defaults matching your script
    $perPage = max(1, (int) $request->input('per_page', 9));
    
    // 2. Start the query
    $query = Article::query();

    // 3. Filter by Category
    // Equivalent to the LEFT JOIN ... WHERE c.name = ?
    if ($request->filled('category')) {
        $category = $request->input('category');
        $query->whereHas('categories', function ($q) use ($category) {
            $q->where('name', $category);
        });
    }

    // 4. Search Filter
    // Equivalent to (n.title LIKE ? OR n.content LIKE ?)
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
        });
    }

    // 5. Order by published_at
    $query->orderBy('published_at', 'desc');

    // 6. Paginate
    // This handles offset, limit, and total count automatically
    $paginator = $query->with('categories')->paginate($perPage);

    // 7. Format the data to match your legacy API structure
    // We transform the collection to create the 'category_names' string manually
    $formattedNews = $paginator->getCollection()->map(function ($article) {
        // Create the string similar to GROUP_CONCAT
        $article->category_names = $article->categories->pluck('name')->implode(', ');
        
        // Optional: Hide the raw relation to keep output clean
        unset($article->categories); 
        
        return $article;
    });

    // 8. Return JSON
    return response()->json([
        'news' => $formattedNews,
        'total' => $paginator->total(),
        'page' => $paginator->currentPage(),
        'per_page' => $paginator->perPage(),
        'total_pages' => $paginator->lastPage()
    ]);
    }
}
