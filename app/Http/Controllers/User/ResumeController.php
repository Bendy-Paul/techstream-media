<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use App\Models\ResumeExperience;
use App\Models\ResumeEducation;
use App\Models\ResumeSkill;
use App\Http\Requests\User\StoreResumeRequest;
use App\Http\Requests\User\UpdateResumeRequest;
use App\Models\User;
use App\Models\Stack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResumeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resumes = Auth::user()->resumes()->latest()->get();
        // Recalculate limits just in case
        $user = Auth::user();
        $limit = $user->resume_limit;
        $count = $resumes->count();
        
        return view('user.resumes.index', compact('resumes', 'limit', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if ($user->resumes()->count() >= $user->resume_limit) {
            return redirect()->route('user.resumes.index')
                ->with('error', 'You have reached your resume limit. Please upgrade to create more.');
        }

        $stacks = Stack::all();
        return view('user.resumes.create', compact('stacks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResumeRequest $request)
    {
        $user = Auth::user();
        if ($user->resumes()->count() >= $user->resume_limit) {
            return redirect()->route('user.resumes.index')
                ->with('error', 'You have reached your resume limit.');
        }

        DB::beginTransaction();

        try {
            // Create Resume
            $resume = $user->resumes()->create([
                'title' => $request->title,
                'summary' => $request->summary,
                'visibility' => $request->visibility,
                'is_default' => $request->boolean('is_default', false),
            ]);

            // Handle Default Toggle
            if ($resume->is_default) {
                $user->resumes()->where('id', '!=', $resume->id)->update(['is_default' => false]);
            }

            // Experience
            if ($request->has('experience')) {
                foreach ($request->experience as $exp) {
                    $resume->experiences()->create($exp);
                }
            }

            // Education
            if ($request->has('education')) {
                foreach ($request->education as $edu) {
                    $resume->education()->create($edu);
                }
            }

            // Skills
            if ($request->filled('skills')) {
                // Skills are now passed as an array of IDs from checkboxes
                $resume->skills()->create(['skills' => $request->skills]);
            }

            DB::commit();

            return redirect()->route('user.resumes.index')->with('success', 'Resume created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating resume: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Resume $resume)
    {
        $this->authorizeResume($resume);
        $resume->load(['experiences', 'education', 'skills']);
        $skillIds = $resume->skills->first()->skills ?? [];
        $skillStacks = collect();
        if (is_array($skillIds) && count($skillIds) > 0) {
            $skillStacks = Stack::whereIn('id', $skillIds)->get();
        }
        
        return view('user.resumes.show', compact('resume', 'skillStacks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resume $resume)
    {
        $this->authorizeResume($resume);
        $resume->load(['experiences', 'education', 'skills']);
        $stacks = Stack::all();
        return view('user.resumes.edit', compact('resume', 'stacks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResumeRequest $request, Resume $resume)
    {
        $this->authorizeResume($resume);

        DB::beginTransaction();

        try {
            // Update Resume details
            $resume->update([
                'title' => $request->title,
                'summary' => $request->summary,
                'visibility' => $request->visibility,
                'is_default' => $request->boolean('is_default', false),
            ]);

            if ($resume->is_default) {
                Auth::user()->resumes()->where('id', '!=', $resume->id)->update(['is_default' => false]);
            }

            // Update Experience
            // Strategy: Sync is hard with separate rows. We'll Delete and Re-create or Update existing.
            // Simple approach for MVP: Delete all and re-create, OR iterate and update if ID exists.
            
            // Let's try smart update/create/delete
            $submittedExpIds = [];
            if ($request->has('experience')) {
                foreach ($request->experience as $expData) {
                    if (isset($expData['id'])) {
                        $exp = $resume->experiences()->find($expData['id']);
                        if ($exp) {
                            $exp->update($expData);
                            $submittedExpIds[] = $exp->id;
                        }
                    } else {
                        $newExp = $resume->experiences()->create($expData);
                        $submittedExpIds[] = $newExp->id;
                    }
                }
            }
            // Delete missing
            $resume->experiences()->whereNotIn('id', $submittedExpIds)->delete();


            // Update Education
            $submittedEduIds = [];
            if ($request->has('education')) {
                foreach ($request->education as $eduData) {
                    if (isset($eduData['id'])) {
                        $edu = $resume->education()->find($eduData['id']);
                        if ($edu) {
                            $edu->update($eduData);
                            $submittedEduIds[] = $edu->id;
                        }
                    } else {
                        $newEdu = $resume->education()->create($eduData);
                        $submittedEduIds[] = $newEdu->id;
                    }
                }
            }
            $resume->education()->whereNotIn('id', $submittedEduIds)->delete();

            // Update Skills
            if ($request->filled('skills')) {
                $resume->skills()->updateOrCreate([], ['skills' => $request->skills]);
            } else {
                $resume->skills()->delete();
            }

            DB::commit();

            return redirect()->route('user.resumes.index')->with('success', 'Resume updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating resume: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resume $resume)
    {
        $this->authorizeResume($resume);
        $resume->delete();
        return redirect()->route('user.resumes.index')->with('success', 'Resume deleted successfully.');
    }

    /**
     * Check if user owns the resume
     */
    private function authorizeResume($resume)
    {
        if ($resume->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
