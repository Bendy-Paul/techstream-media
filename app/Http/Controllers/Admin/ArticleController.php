<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Company;
use App\Models\Event;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Requests\Admin\StoreArticleRequest;
use App\Http\Requests\Admin\UpdateArticleRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $articles = Article::all();
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $companies = Company::all();
        $events = Event::all();
        $categories = Category::where('type', 'article')->get();
        $tags = Tag::all();
        return view('admin.articles.create', compact('companies', 'events', 'categories', 'tags'));


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        //
          // 1. If we reach this line, Validation has ALREADY PASSED.
        
        // 2. Handle Image Logic
        // Determine if we are using an uploaded file or a gallery selection
        $imagePath = null;

        if ($request->hasFile('featured_image_upload')) {
            // Upload new file to 'public/uploads/articles'
            $imagePath = $request->file('featured_image_upload')->store('uploads/articles', 'public');
        } elseif ($request->filled('selected_image')) {
            // Use existing gallery path
            $imagePath = $request->selected_image;
        }

        // 3. Database Transaction
        // We use a transaction so if saving tags fails, the article isn't created.
        // It ensures data integrity (all or nothing).
        
        DB::transaction(function () use ($request, $imagePath) { 
            
            // Create the Article
            $article = Article::create([
                'title' => $request->title,
                'author_id' => $request->author_id,
                'slug' => Str::slug($request->title), // Generate slug from title
                'content' => $request->content,
                'status' => $request->status,
                'is_featured' => $request->has('is_featured'), // Checkbox returns on/off or null
                'featured_image_url' => $imagePath,
            ]);

            // 4. Sync Relationships
            // 'sync' is safer than 'attach' because it prevents duplicate entries.
            // It looks at the array of IDs (e.g., [1, 5, 9]) and ensures the DB matches exactly.
            
            if ($request->has('companies')) {
                $article->companies()->sync($request->companies);
            }

            if ($request->has('events')) {
                $article->events()->sync($request->events);
            }
            
            if ($request->has('tags')) {
                $article->tags()->sync($request->tags);
            }

            if ($request->has('categories')) {
                $article->categories()->sync($request->categories);
            }
        });

        // 5. Redirect with Flash Message
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article published successfully!');
    

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $article = Article::with(['companies', 'events', 'tags', 'categories'])->findOrFail($id);
        $companies = Company::all();
        $events = Event::all();
        $categories = Category::where('type', 'article')->get();
        $tags = Tag::all();
        
        return view('admin.articles.edit', compact('article', 'companies', 'events', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, $id)
    {
        $article = Article::findOrFail($id);

        // 1. Handle Image Logic
        $imagePath = $article->featured_image_url; // Default to existing

        if ($request->hasFile('featured_image_upload')) {
            // Upload new file
            if ($article->featured_image_url && Storage::disk('public')->exists($article->featured_image_url)) {
                // Optional: Delete old image if strictly 1-to-1 and not reused
                // Storage::disk('public')->delete($article->featured_image_url);
            }
            $path = $request->file('featured_image_upload')->store('uploads/articles', 'public');
            $imagePath = 'storage/' . $path;
        } elseif ($request->filled('selected_image')) {
            // Use selected gallery image
            $imagePath = $request->selected_image;
        } elseif ($request->input('remove_image') == '1') {
             $imagePath = null;
        }

        // 2. Database Transaction
        DB::transaction(function () use ($request, $article, $imagePath) {
            
            // Update Article Basic Fields
            $article->update([
                'title' => $request->title,
                // 'author_id' => $request->author_id, // Usually keep original author
                'slug' => Str::slug($request->title),
                'content' => $request->content,
                'status' => $request->status,
                'is_featured' => $request->has('is_featured'),
                'featured_image_url' => $imagePath,
            ]);

            // 3. Sync Relationships
            // Sync replaces existing associations with the new array.
            // If the array is empty (checkboxes unchecked), it detaches all.
            
            $article->companies()->sync($request->input('companies', []));
            $article->events()->sync($request->input('events', []));
            $article->tags()->sync($request->input('tags', []));
            $article->categories()->sync($request->input('categories', []));
        });

        // 4. Redirect
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $article = Article::findOrFail($id);
        $article->delete();
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article deleted successfully!');
    }
}
