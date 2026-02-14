<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of applicants for a specific job.
     */
    public function index(Request $request) 
    {
        // If 'job_id' is provided, show applicants for that job
        if ($request->has('job_id')) {
            $job = Job::findOrFail($request->job_id);
            $applications = $job->applications()
                                ->with('user')
                                ->orderByDesc('match_score')
                                ->paginate(15);
            
            return view('admin.jobs.applicants', compact('job', 'applications'));
        }

        // Otherwise lists all applications (maybe not needed immediately, but good for overview)
        // or redirect to jobs index
        return redirect()->route('admin.jobs.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $application = JobApplication::with(['job', 'user', 'resume'])->findOrFail($id);
        
        // Decode snapshot if needed (should be automatic via cast)
        // $snapshot = $application->resume_snapshot;

        return view('admin.applications.show', compact('application'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:applied,shortlisted,interviewing,rejected,hired,withdrawn',
        ]);

        $application = JobApplication::findOrFail($id);
        $application->update(['status' => $request->status]);

        // Optional: Send email notification to user about status change

        return back()->with('success', 'Application status updated successfully.');
    }
}
