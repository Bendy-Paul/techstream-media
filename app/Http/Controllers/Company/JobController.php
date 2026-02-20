<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Category;

class JobController extends Controller
{
    public function index()
    {
        $company = auth()->user()->company;
        $jobs = $company->jobs()->latest()->paginate(10);
        return view('company.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $categories = Category::all();
        // and other needed data (e.g. cities, types)
        return view('company.jobs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $company = auth()->user()->company;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            // add more validation rules as needed based on jobs table
        ]);

        $validated['company_id'] = $company->id;
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title'] . '-' . uniqid());
        $validated['status'] = 'draft'; // or active

        Job::create($validated);

        return redirect()->route('company.jobs.index')->with('success', 'Job created successfully.');
    }

    public function edit(Job $job)
    {
        if ($job->company_id !== auth()->user()->company->id) {
            abort(403);
        }
        $categories = Category::all();
        return view('company.jobs.edit', compact('job', 'categories'));
    }

    public function update(Request $request, Job $job)
    {
        if ($job->company_id !== auth()->user()->company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            // add more validation rules
        ]);

        $job->update($validated);
        return redirect()->route('company.jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        if ($job->company_id !== auth()->user()->company->id) {
            abort(403);
        }
        $job->delete();
        return redirect()->route('company.jobs.index')->with('success', 'Job deleted successfully.');
    }
}
