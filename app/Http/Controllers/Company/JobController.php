<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\City;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $company = $user->company;
        $jobs = $company->jobs()->latest()->paginate(10);
        return view('company.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $cities = City::orderBy('name')->get();
        return view('company.jobs.create', compact('cities'));
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $company = $user->company;

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'summary'          => 'required|string|max:500',
            'description'      => 'required|string',
            'responsibilities' => 'nullable|string',
            'requirements'     => 'nullable|string',
            'job_type'         => 'required|in:Full-time,Part-time,Contract,Internship,Remote',
            'experience_level' => 'required|in:Entry,Mid,Senior,Lead',
            'education_level'  => 'nullable|string|max:100',
            'salary_range'     => 'nullable|string|max:100',
            'city_id'          => 'nullable|exists:cities,id',
            'is_remote'        => 'boolean',
            'application_type' => 'required|in:smart_apply,external',
            'apply_link'       => 'nullable|string|max:255',
            'expires_at'       => 'required|date|after:today',
        ]);

        $validated['company_id']       = $company->id;
        $validated['slug']             = Str::slug($validated['title']) . '-' . uniqid();
        $validated['status']           = 'active';
        $validated['is_remote']        = $request->boolean('is_remote');

        Job::create($validated);

        return redirect()->route('company.jobs.index')->with('success', 'Job posted successfully.');
    }

    public function edit(Job $job)
    {
        $this->authorizeJob($job);
        $cities = City::orderBy('name')->get();
        return view('company.jobs.edit', compact('job', 'cities'));
    }

    public function update(Request $request, Job $job)
    {
        $this->authorizeJob($job);

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'summary'          => 'required|string|max:500',
            'description'      => 'required|string',
            'responsibilities' => 'nullable|string',
            'requirements'     => 'nullable|string',
            'job_type'         => 'required|in:Full-time,Part-time,Contract,Internship,Remote',
            'experience_level' => 'required|in:Entry,Mid,Senior,Lead',
            'education_level'  => 'nullable|string|max:100',
            'salary_range'     => 'nullable|string|max:100',
            'city_id'          => 'nullable|exists:cities,id',
            'is_remote'        => 'boolean',
            'application_type' => 'required|in:smart_apply,external',
            'apply_link'       => 'nullable|string|max:255',
            'expires_at'       => 'required|date',
        ]);

        $validated['is_remote'] = $request->boolean('is_remote');

        $job->update($validated);

        return redirect()->route('company.jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        $this->authorizeJob($job);
        $job->delete();
        return redirect()->route('company.jobs.index')->with('success', 'Job deleted successfully.');
    }

    private function authorizeJob(Job $job): void
    {
        if ($job->company_id !== Auth::user()->company?->id) {
            abort(403);
        }
    }
}
