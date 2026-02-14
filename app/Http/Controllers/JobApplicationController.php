<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'consent' => 'required|accepted', // Ensure consent is checked
        ]);

        $user = Auth::user();
        $job = Job::findOrFail($request->job_id);

        // Check if already applied
        if ($user->applications()->where('job_id', $job->id)->exists()) {
            return back()->with('error', 'You have already applied for this job.');
        }

        // Get User's Default or First Resume
        // Ideally user selects one, but for Smart Apply prompt "Use my profile", we often use the default.
        // Let's assume the user has a resume.
        $resume = $user->resumes()->where('is_default', true)->first() ?? $user->resumes()->first();

        if (!$resume) {
            return redirect()->route('user.resumes.create')->with('error', 'Please create a resume to apply.');
        }

        // Create Snapshot
        // We load the resume with its experiences and education
        $resume->load(['experiences', 'education']);
        $snapshot = $resume->toArray();

        // Process Skills to include Names (since ResumeSkill only stores IDs)
        $skillIds = $resume->skills->pluck('skills')->flatten()->unique()->toArray();
        $skills = \App\Models\Stack::whereIn('id', $skillIds)->get(['id', 'name', 'icon_class'])->toArray();
        
        $snapshot['skills'] = $skills; // Override strict ID-only relationship data with full stack data

        // Calculate Match Score
        $matchScore = $job->calculateMatchScore($resume);

        // Store Application
        JobApplication::create([
            'job_id' => $job->id,
            'user_id' => $user->id,
            'resume_id' => $resume->id,
            'match_score' => $matchScore,
            'resume_snapshot' => $snapshot,
            'status' => 'applied',
            'match_details' => [], // Can be populated if calculateMatchScore returns details
        ]);

        return redirect()->route('user.applications.index')->with('success', 'Application submitted successfully!');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applications = Auth::user()->applications()->with('job.company')->latest()->get();
        return view('user.applications.index', compact('applications'));
    }

    /**
     * Remove the specified resource from storage (Withdraw).
     */
    public function destroy(string $id)
    {
        $application = Auth::user()->applications()->findOrFail($id);
        
        // Logical deletion or status update?
        // Task says "Withdraw". Let's update status to withdrawn.
        $application->update(['status' => 'withdrawn']);

        return back()->with('success', 'Application withdrawn.');
    }
}
