<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class ApplicationController extends Controller
{
    /**
     * List all applicants for a specific company job.
     */
    public function index(Request $request, Job $job)
    {
        $this->authorizeJob($job);

        // Sort/filter parameters
        $sort      = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        $status    = $request->input('status');

        $allowedSorts = ['created_at', 'match_score', 'status'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        $query = $job->applications()
            ->with(['user'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy($sort, $direction);

        $applications = $query->paginate(20)->withQueryString();

        // Summary metrics
        $metrics = [
            'total'       => $job->applications()->count(),
            'new'         => $job->applications()->where('status', 'applied')->count(),
            'shortlisted' => $job->applications()->where('status', 'shortlisted')->count(),
            'interviewing' => $job->applications()->where('status', 'interviewing')->count(),
            'hired'       => $job->applications()->where('status', 'hired')->count(),
            'rejected'    => $job->applications()->where('status', 'rejected')->count(),
            'avg_score'   => round($job->applications()->avg('match_score') ?? 0),
            'top_score'   => $job->applications()->max('match_score') ?? 0,
        ];

        return view('company.applications.index', compact('job', 'applications', 'metrics', 'sort', 'direction', 'status'));
    }

    /**
     * Update the status of a single application.
     */
    public function update(Request $request, Job $job, JobApplication $application)
    {
        $this->authorizeJob($job);

        $request->validate([
            'status' => 'required|in:applied,shortlisted,interviewing,offered,hired,rejected,withdrawn',
        ]);

        $application->update(['status' => $request->status]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'status' => $application->status]);
        }

        return back()->with('success', 'Application status updated.');
    }

    /**
     * Bulk update status for multiple applications.
     */
    public function bulkUpdate(Request $request, Job $job)
    {
        $this->authorizeJob($job);

        $request->validate([
            'application_ids'   => 'required|array',
            'application_ids.*' => 'exists:job_applications,id',
            'status'            => 'required|in:applied,shortlisted,interviewing,offered,hired,rejected',
        ]);

        // Confirm all selected applications belong to this job
        $updated = JobApplication::whereIn('id', $request->application_ids)
            ->where('job_id', $job->id)
            ->update(['status' => $request->status]);

        return back()->with('success', "$updated application(s) updated to " . ucfirst($request->status) . '.');
    }

    private function authorizeJob(Job $job): void
    {
        if ($job->company_id !== Auth::user()->company?->id) {
            abort(403);
        }
    }
}
