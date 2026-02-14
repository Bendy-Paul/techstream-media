<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Models\Article;

class HomeController extends Controller
{
    public function index()
    {
        // 1. MAP LOGIC: Fetch stats per state
        // We use DB::select for complex raw SQL queries to keep it fast
        $mapStats = DB::select("
SELECT 
    s.id,
    s.name as state_name,
    s.state_code as state_code,
    COUNT(DISTINCT comp.id) as company_count
FROM states s
LEFT JOIN cities c ON s.id = c.state_id
LEFT JOIN companies comp ON c.id = comp.city_id
GROUP BY s.id, s.name, s.state_code
ORDER BY company_count DESC
        ");

        // Calculate highest count for the color intensity logic
        $highestCount = 0;
        foreach ($mapStats as $stat) {
            if ($stat->company_count > $highestCount) {
                $highestCount = $stat->company_count;
            }
        }
        // Avoid division by zero if DB is empty
        $highestCount = $highestCount > 0 ? $highestCount : 1;


        // 2. RECENT LISTINGS (Limit 6)
        // We assume Company has a relationship to City
        $recentListings = Company::leftJoin('cities', 'companies.city_id', '=', 'cities.id')
            ->select('companies.*', 'cities.name as city_name')
            ->orderBy('companies.created_at', 'desc')
            ->limit(6)
            ->get();


        // 3. NEWS / ARTICLES
        $featuredNews = Article::where('status', 'published')
            ->where('is_featured', 1)
            ->latest('published_at')
            ->first();

        $regularNews = Article::where('status', 'published')
            ->orderBy('is_featured', 'desc') // Featured ones first if needed
            ->latest('published_at')
            ->limit(4)
            ->get();

        // 4. Send everything to the view
        return view('public.home', compact('mapStats', 'highestCount', 'recentListings', 'featuredNews', 'regularNews'));
    }
}
