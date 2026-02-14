<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Company;
use App\Models\City;
use App\Models\Tools;
use App\Models\Keyword; // Add this line
use App\Http\Requests\Admin\StoreJobRequest;
use App\Http\Requests\Admin\UpdateJobRequest;
use Illuminate\Support\Str;


class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $jobs = Job::all();
        return view('admin.jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $companies = Company::all();
        $cities = City::all();
        $tools = Tools::all();
        $keywords = Keyword::all();
        return view('admin.jobs.create', compact('companies', 'cities', 'tools', 'keywords'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(StoreJobRequest $request)
{
    $job = Job::create([
        'title' => $request->title,
        'slug' => Str::slug($request->title).'-'.uniqid(),
        'company_id' => $request->company_id,
        'description' => $request->description,
        'summary' => $request->summary,
        'responsibilities' => $request->responsibilities,
        'requirements' => $request->requirements,
        'job_type' => $request->job_type,
        'experience_level' => $request->experience_level,
        'education_level' => $request->education_level,
        'salary_range' => $request->salary_range,
        'application_type' => $request->application_type,
        'apply_link' => $request->apply_link,
        'city_id' => $request->location_id, // Front-end sends location_id as city ID
        'location' => $request->location_text ?? '', // Fallback or specific field
        'is_remote' => $request->boolean('is_remote'),
        'expires_at' => $request->expires_at,
    ]);

    if ($request->filled('tool_ids')) {
        $job->tools()->sync($request->tool_ids);
    }

    if ($request->filled('keywords')) {
        // Expected format: ['keyword_id' => weight, 'keyword_id' => weight]
        // Or if sending array of objects, need to parse.
        // Let's assume the view sends an array of keyword IDs and a separate array of weights, 
        // or a structured array like keywords[id] = weight.
        // Simplest for sync is [id => ['weight' => val]]
        
        $keywordsSyncData = [];
        foreach ($request->keywords as $keywordId => $weight) {
             $keywordsSyncData[$keywordId] = ['weight' => $weight];
        }
        $job->keywords()->sync($keywordsSyncData);
    }

    return redirect()
        ->route('admin.jobs.create')
        ->with('success', 'Job posted successfully');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $job = Job::with(['company', 'tools', 'keywords'])->findOrFail($id);
        $companies = Company::all();
        $cities = City::all();
        $tools = Tools::all();
        $keywords = Keyword::all();
        
        return view('admin.jobs.edit', compact('job', 'companies', 'cities', 'tools', 'keywords'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobRequest $request, $id)
    {
        $job = Job::findOrFail($id);
        
        $job->update([
            'title' => $request->title,
            // 'slug' => ... // Usually update slug only if title changes or keep it constant. Keeping constant for SEO is better.
            'company_id' => $request->company_id,
            'description' => $request->description,
            'summary' => $request->summary,
            'responsibilities' => $request->responsibilities,
            'requirements' => $request->requirements,
            'job_type' => $request->job_type,
            'experience_level' => $request->experience_level,
            'education_level' => $request->education_level,
            'salary_range' => $request->salary_range,
            'application_type' => $request->application_type,
            'apply_link' => $request->apply_link,
            'city_id' => $request->location_id,
            'location' => $request->location_text ?? '',
            'is_remote' => $request->boolean('is_remote'),
            'expires_at' => $request->expires_at,
        ]);

        if ($request->filled('tool_ids')) {
            $job->tools()->sync($request->tool_ids);
        } else {
            $job->tools()->detach();
        }

        if ($request->filled('keywords')) {
            $keywordsSyncData = [];
            foreach ($request->keywords as $keywordId => $weight) {
                 $keywordsSyncData[$keywordId] = ['weight' => $weight];
            }
            $job->keywords()->sync($keywordsSyncData);
        } else {
            $job->keywords()->detach();
        }

        return redirect()->route('admin.jobs.index')->with('success', 'Job updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $job = Job::findOrFail($id);
        $job->delete();
        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'Job deleted successfully');
    }
}
