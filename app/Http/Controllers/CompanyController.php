<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyProject;
use App\Models\CompanyGallery;
use App\Models\Article;
use App\Models\Tools;
use App\Models\Category;
use App\Models\Branch;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function showPremium($slug)
    {
        // Eager load relationships
        $company = Company::with(['categories', 'branches.city'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Fetch related data
        $projects = CompanyProject::where('company_id', $company->id)->get();
        $gallery = CompanyGallery::where('company_id', $company->id)->get();
        $categories = $company->categories;
        
        // Get company news
        $company_news = Article::whereHas('companies', function ($query) use ($company) {
            $query->where('companies.id', $company->id);
        })
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        // Parse JSON data
        $socials = json_decode($company->social_links ?? '{}', true) ?: [];
        $stats = json_decode($company->profile_stats ?? '{}', true) ?: [];

        // Tech stacks
        $stackIds = json_decode($company->stack_ids ?? '[]', true) ?: [];
        $stacks = collect();
        
        if (!empty($stackIds)) {
            $stacks = Tools::whereIn('id', $stackIds)
                ->orderByRaw('FIELD(id, ' . implode(',', $stackIds) . ')')
                ->get();
        }

        // Branches
        $branches = $company->branches;

        // Profile strength calculation
        $profileFields = ['name', 'tagline', 'description', 'email', 'phone', 
                         'website_url', 'year_founded', 'team_size', 'logo_url', 'address'];
        
        $filled = 0;
        foreach ($profileFields as $field) {
            if (!empty($company->$field)) $filled++;
        }
        
        $totalFields = count($profileFields);
        $profile_strength = $totalFields > 0 ? round(($filled / $totalFields) * 100) : 0;

        // Get category IDs
        $categoryIds = $categories->pluck('id')->toArray();
        
        // Initialize variables
        $avgStrength = null;
        $avgPeerAge = null;
        
        // Calculate peer metrics if company has categories
        if (!empty($categoryIds)) {
            // FIXED: Corrected the relationship query
            // Assuming your pivot table is 'company_categories' with columns 'company_id' and 'category_id'
            $peers = Company::whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('category_id', $categoryIds);
                })
                ->where('id', '!=', $company->id)
                ->get(['id', 'year_founded']);
            
            // Calculate average profile strength
            $peerStrengths = [];
            foreach ($peers as $peer) {
                $peerFilled = 0;
                foreach ($profileFields as $field) {
                    if (!empty($peer->$field)) $peerFilled++;
                }
                $peerStrengths[] = $totalFields > 0 ? round(($peerFilled / $totalFields) * 100) : 0;
            }
            
            $avgStrength = !empty($peerStrengths) ? round(array_sum($peerStrengths) / count($peerStrengths)) : null;

            // Calculate average peer age
            $currentYear = (int) date('Y');
            $peerAges = [];
            
            foreach ($peers as $peer) {
                if ($peer->year_founded && $peer->year_founded > 1900) {
                    $peerAges[] = $currentYear - (int)$peer->year_founded;
                }
            }
            
            $avgPeerAge = !empty($peerAges) ? round(array_sum($peerAges) / count($peerAges), 1) : null;
        }

        // Company age
        $companyYear = (int) ($company->year_founded ?? 0);
        $companyAge = ($companyYear > 1900) ? (date('Y') - $companyYear) : null;

        // Determine view
        $viewName = $company->is_featured == 1 ? 'public.company.featured' : 'public.company.standard';
        
        return view($viewName, compact(
            'company',
            'projects',
            'gallery',
            'company_news',
            'categories',
            'socials',
            'stats',
            'stacks',
            'branches',
            'profile_strength',
            'avgStrength',
            'companyAge',
            'avgPeerAge'
        ));
    }
}