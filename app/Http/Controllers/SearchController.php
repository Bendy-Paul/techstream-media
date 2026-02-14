<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Event;
use App\Models\Article;
use App\Models\Job;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $searchQuery = $request->get('s', '');
        $locationFilter = $request->get('loc', '');
        $sortBy = $request->get('sort', 'relevance');
        
        // Determine which types to search
        $types = [];
        if ($request->has('type_company')) $types[] = 'company';
        if ($request->has('type_event')) $types[] = 'event';
        if ($request->has('type_article')) $types[] = 'article';
        if ($request->has('type_job')) $types[] = 'job';
        
        // If no specific types selected, search all
        if (empty($types)) {
            $types = ['company', 'event', 'article', 'job'];
        }

        $results = collect();

        // --- 1. Companies ---
        if (in_array('company', $types)) {
            $companies = Company::query()
                ->with('city')
                ->when($searchQuery, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                          ->orWhere('description', 'LIKE', "%{$search}%")
                          ->orWhere('tagline', 'LIKE', "%{$search}%");
                    });
                })
                ->when($locationFilter, function ($query, $location) {
                    $query->whereHas('city', function ($q) use ($location) {
                        $q->where('name', 'LIKE', "%{$location}%");
                    })->orWhere('address', 'LIKE', "%{$location}%");
                })
                ->get()
                ->map(function ($company) {
                    return (object) [
                        'type' => 'company',
                        'id' => $company->id,
                        'title' => $company->name,
                        'description' => $company->tagline ?? $company->description,
                        'image_url' => $company->logo_url,
                        'meta_1' => optional($company->city)->name,
                        'meta_2' => $company->team_size,
                        'action_text' => 'View Profile',
                        'url_slug' => $company->slug,
                        'created_at' => $company->created_at,
                    ];
                });
            $results = $results->merge($companies);
        }

        // --- 2. Events ---
        if (in_array('event', $types)) {
            $events = Event::query()
                ->with('city')
                ->where('start_datetime', '>=', now())
                ->when($searchQuery, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'LIKE', "%{$search}%")
                          ->orWhere('description', 'LIKE', "%{$search}%");
                    });
                })
                ->when($locationFilter, function ($query, $location) {
                    $query->where(function ($q) use ($location) {
                        $q->where('location_name', 'LIKE', "%{$location}%")
                          ->orWhereHas('city', function ($subQ) use ($location) {
                              $subQ->where('name', 'LIKE', "%{$location}%");
                          });
                    });
                })
                ->get()
                ->map(function ($event) {
                    return (object) [
                        'type' => 'event',
                        'id' => $event->id,
                        'title' => $event->title,
                        'description' => $event->description,
                        'image_url' => $event->banner_image_url,
                        'meta_1' => $event->location_name,
                        'meta_2' => $event->start_datetime,
                        'action_text' => 'View Event',
                        'url_slug' => $event->slug,
                        'created_at' => $event->created_at,
                    ];
                });
            $results = $results->merge($events);
        }

        // --- 3. Articles ---
        if (in_array('article', $types)) {
            // Articles usually don't have location, so only search if location filter is empty
            if (empty($locationFilter)) {
                $articles = Article::query()
                    ->with('user')
                    ->where('status', 'published')
                    ->when($searchQuery, function ($query, $search) {
                        $query->where(function ($q) use ($search) {
                            $q->where('title', 'LIKE', "%{$search}%")
                              ->orWhere('content', 'LIKE', "%{$search}%");
                        });
                    })
                    ->get()
                    ->map(function ($article) {
                        return (object) [
                            'type' => 'article',
                            'id' => $article->id,
                            'title' => $article->title,
                            'description' => $article->content, // You might want to strip tags / limit this in view
                            'image_url' => $article->featured_image_url,
                            'meta_1' => optional($article->user)->full_name,
                            'meta_2' => $article->created_at->format('M d, Y'),
                            'action_text' => 'Read Article',
                            'url_slug' => $article->slug,
                            'created_at' => $article->created_at,
                        ];
                    });
                $results = $results->merge($articles);
            }
        }

        // --- 4. Jobs ---
        if (in_array('job', $types)) {
            $jobs = Job::query()
                ->with(['company', 'city']) // Assuming relationships exist
                ->where('expires_at', '>=', now())
                ->when($searchQuery, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'LIKE', "%{$search}%")
                          ->orWhere('description', 'LIKE', "%{$search}%")
                          ->orWhereHas('company', function ($subTwo) use ($search) {
                                $subTwo->where('name', 'LIKE', "%{$search}%");
                          });
                    });
                })
                ->when($locationFilter, function ($query, $location) {
                     $query->where('location', 'LIKE', "%{$location}%")
                           ->orWhereHas('city', function ($q) use ($location) {
                               $q->where('name', 'LIKE', "%{$location}%");
                           });
                })
                ->get()
                ->map(function ($job) {
                    return (object) [
                        'type' => 'job',
                        'id' => $job->id,
                        'title' => $job->title,
                        'description' => $job->description,
                        'image_url' => optional($job->company)->logo_url, // Use company logo if available
                        'meta_1' => $job->location ?? optional($job->city)->name,
                        'meta_2' => optional($job->company)->name,
                        'action_text' => 'View Job',
                        'url_slug' => $job->slug,
                        'created_at' => $job->created_at,
                    ];
                });
            $results = $results->merge($jobs);
        }

        // --- 5. Sorting ---
        if ($sortBy === 'newest') {
            $results = $results->sortByDesc('created_at')->values();
        } else {
            // Default sort (Relevance/Random/Etc). For now, just newest or simple mix.
            // If strict relevance is needed, we'd assign scores. 
            // Here we'll just default to created_at desc as a baseline for "relevance" if no better metric.
            $results = $results->sortByDesc('created_at')->values();
        }

        // --- 6. Pagination ---
        $page = $request->get('page', 1);
        $perPage = 10;
        
        $paginatedResults = new LengthAwarePaginator(
            $results->forPage($page, $perPage),
            $results->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('public.search-results', [
            'results' => $paginatedResults,
            'searchParams' => $request->all(),
            'totalPages' => $paginatedResults->lastPage(), // Maintain compatibility if view uses this
            'totalResults' => $paginatedResults->total(),   // Maintain compatibility
            'currentPage' => $paginatedResults->currentPage() // Maintain compatibility
        ]);
    }
}