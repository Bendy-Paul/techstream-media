<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Job;
use App\Models\Company;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $resumeCount = $user->resumes()->count();
        $resumeLimit = $user->resume_limit;
        $savedItemsCount = $user->savedItems()->count();

        return view('user.dashboard', compact('resumeCount', 'resumeLimit', 'savedItemsCount'));
    }

    public function savedItems()
    {
        $user = auth()->user();
        
        $savedJobs = $user->savedItems()
            ->where('item_type', Job::class)
            ->with('item.company', 'item.city') // Eager load relationships
            ->get()
            ->pluck('item')
            ->filter(); // Filter out nulls in case item was deleted

        $savedCompanies = $user->savedItems()
            ->where('item_type', Company::class)
            ->with('item.city')
            ->get()
            ->pluck('item')
            ->filter();

        $savedEvents = $user->savedItems()
            ->where('item_type', Event::class)
            ->with('item.city')
            ->get()
            ->pluck('item')
            ->filter();

        return view('user.saved-items', compact('savedJobs', 'savedCompanies', 'savedEvents'));
    }

    public function applications()
    {
        return view('user.applications');
    }

    public function settings()
    {
        return view('user.settings');
    }
}
