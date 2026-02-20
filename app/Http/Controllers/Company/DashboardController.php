<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $company = $user->company;

        $activeJobsCount = 0;
        $previousJobsCount = 0;
        $totalApplicants = 0;
        $totalEvents = 0;

        if ($company) {
            $activeJobsCount = $company->jobs()->active()->count();
            // Previous jobs are those not in 'active' status or expired
            $previousJobsCount = $company->jobs()->where(function ($query) {
                $query->where('status', '!=', 'active')
                    ->orWhere('expires_at', '<=', now());
            })->count();

            // Total applications across all jobs for this company
            $totalApplicants = \App\Models\JobApplication::whereHas('job', function ($q) use ($company) {
                $q->where('company_id', $company->id);
            })->count();

            // Total events for this company (events added directly + events where they are partners/co-organizers)
            // Simplified to: events where this company is the organizer owner, or co-organizer
            $totalEvents = \App\Models\Event::whereHas('organizer', function ($q) use ($company) {
                $q->where('owner_type', \App\Models\Company::class)
                    ->where('owner_id', $company->id);
            })->orWhereHas('coOrganizers', function ($q) use ($company) {
                $q->where('company_id', $company->id);
            })->count();
        }

        // Ensure activities relationship exists on User, or create logic
        $activities = $user->activities()->latest()->take(10)->get();

        return view('company.dashboard', compact('company', 'activeJobsCount', 'previousJobsCount', 'totalApplicants', 'totalEvents', 'activities'));
    }
}
