<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;

class JobController extends Controller
{
        public function index(Request $request)
    {
        $query = Job::with(['company', 'tools', 'company.city'])->where('status', 'active');

        // Search (Title, Description, or Company Name)
        if ($request->filled('s')) {
            $search = $request->s;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('company', function($c) use ($search) {
                      $c->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Location (Company Address or City)
        if ($request->filled('loc')) {
            $loc = $request->loc;
            $query->whereHas('company', function($q) use ($loc) {
                $q->where('address', 'like', "%{$loc}%")
                  ->orWhereHas('city', function($c) use ($loc) {
                      $c->where('name', 'like', "%{$loc}%");
                  });
            });
        }

        // Job Type
        if ($request->filled('type') && $request->type !== 'Job Type') {
            $query->where('job_type', $request->type);
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        return view('public.jobs', compact('jobs'));
    }
    // ... existing index method ...

    public function show($slug)
    {
        $job = Job::with(['company', 'tools', 'company.city'])->where('slug', $slug)->firstOrFail();
        
        $matchScore = null;

        if (Auth::check() && Auth::user()->role === 'user') {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            // Get user's default resume or first resume
            $resume = $user->resumes()->where('is_default', 1)->first() 
                      ?? $user->resumes()->first();

            if ($resume) {
                $matchScore = $job->calculateMatchScore($resume);
            }
        }

        return view('public.job-detail', compact('job', 'matchScore'));
    }
}
