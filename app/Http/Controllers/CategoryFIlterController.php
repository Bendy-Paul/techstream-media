<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CategoryFIlterController extends Controller
{
    //
    
    public function index(Request $request)
    {
        $page     = max(1, (int) $request->query('page', 1));
        $perPage  = max(1, (int) $request->query('per_page', 9));
        $offset   = ($page - 1) * $perPage;

        $category            = $request->query('category_id');
        $selectedCategories  = (array) $request->query('categories', []);
        $search              = $request->query('search');
        $state               = $request->query('location');
        $verified            = $request->query('verified');
        $priceRange          = $request->query('price_range');
        $teamSize            = $request->query('team_size');

        $query = DB::table('companies as c')
            ->leftJoin('company_categories as cat', 'c.id', '=', 'cat.company_id')
            ->select('c.*')
            ->distinct();

        /* Category filters */
        if ($category) {
            $query->where('cat.category_id', $category);
        }

        if (!empty($selectedCategories)) {
            $query->where(function ($q) use ($selectedCategories) {
                foreach ($selectedCategories as $catId) {
                    $q->orWhere('cat.category_id', $catId);
                }
            });
        }

        /* State */
        if ($state) {
            $query->where('c.state_id', $state);
        }

        /* Verified */
        if ($verified !== null) {
            $query->where('c.is_verified', (int) $verified);
        }

        /* Price range */
        if ($priceRange) {
            match ($priceRange) {
                '500000'   => $query->where('c.price_range', '<', 500000),
                '2000000'  => $query->whereBetween('c.price_range', [500000, 2000000]),
                '5000000'  => $query->whereBetween('c.price_range', [2000000, 5000000]),
                '10000000' => $query->where('c.price_range', '>', 5000000),
                default    => null
            };
        }

        /* Team size */
        if ($teamSize) {
            match ($teamSize) {
                '1-10'   => $query->whereBetween('c.team_size', [1, 10]),
                '11-50'  => $query->whereBetween('c.team_size', [11, 50]),
                '51-200' => $query->whereBetween('c.team_size', [51, 200]),
                '200+'   => $query->where('c.team_size', '>', 200),
                default  => null
            };
        }

        /* Search */
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('c.name', 'like', "%{$search}%")
                  ->orWhere('c.tagline', 'like', "%{$search}%");
            });
        }

        /* Clone query for count */
        $countQuery = clone $query;
        $total = DB::table(DB::raw("({$countQuery->toSql()}) as sub"))
            ->mergeBindings($countQuery)
            ->count();
            
        /* Fetch paginated results */
        $companies = $query
            ->orderBy('c.name', 'asc')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        return response()->json([
            'companies'    => $companies,
            'total'        => $total,
            'page'         => $page,
            'per_page'     => $perPage,
            'total_pages'  => (int) ceil($total / $perPage),
        ]);
    }
}
