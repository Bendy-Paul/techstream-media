<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Category;
use App\Models\City;
use App\Models\Tools;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\StoreCompanyRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\UpdateCompanyRequest;


class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $companies = Company::all();
        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $cities = City::all();
        $categories = Category::all();
        $stacks = Tools::all();
        return view('admin.companies.create', compact('cities', 'categories', 'stacks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        try {
            DB::beginTransaction();

            // Create company
            $company = Company::create([
                'name'              => $request->name,
                'user_id'           => Auth::id(),
                'slug'              => Str::slug($request->name) . '-' . Str::random(6),
                'tagline'           => $request->tagline,
                'description'       => $request->description,
                'city_id'           => $request->city_id,
                'address'           => $request->address,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'website_url'       => $request->website_url,
                'year_founded'      => $request->year_founded,
                'team_size'         => $request->team_size,
                'starting_cost'     => $request->starting_cost,
                'subscription_tier' => $request->subscription_tier,
                'is_verified'       => $request->boolean('is_verified'),
                'is_featured'       => $request->boolean('is_featured'),
            ]);

            /* --------------------
         | Upload files
         -------------------- */
            if ($request->hasFile('logo')) {
                $company->logo_url = $request->file('logo')->store('companies/logos', 'public');
            }

            if ($request->hasFile('cover')) {
                $company->cover_image_url = $request->file('cover')->store('companies/covers', 'public');
            }

            $company->save();

            /* --------------------
         | Pivot relations
         -------------------- */
            if ($request->categories) {
                $company->categories()->sync($request->categories);
            }

            $company->save();

            DB::commit();

            return redirect()
                ->route('admin.companies.index')
                ->with('success', 'Company created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Something went wrong while creating the company.' . $e->getMessage()]);
        }
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
    public function edit($id)
    {
        $cities = City::all();
        $categories = Category::all();
        $stacks = Tools::all();
        $company = Company::findOrFail($id);
        return view('admin.companies.edit', compact('company', 'cities', 'categories', 'stacks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $company = Company::findOrFail($id);

            // Update basic info
            $company->update([
                'name'              => $request->name,
                'tagline'           => $request->tagline,
                'description'       => $request->description,
                'city_id'           => $request->city_id,
                'address'           => $request->address,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'website_url'       => $request->website_url,
                'year_founded'      => $request->year_founded,
                'team_size'         => $request->team_size,
                'starting_cost'     => $request->starting_cost,
                'subscription_tier' => $request->subscription_tier,
                'is_verified'       => (int) $request->is_verified,
                'is_featured'       => (int) $request->is_featured,
            ]);

            // Manually handle JSON columns if not fillable/mapped
            $company->social_links = $request->social;
            $company->profile_stats = $request->stats;
            $company->save();

            /* --------------------
             | Upload files
             -------------------- */
            if ($request->hasFile('logo')) {
                // Delete old
                if ($company->logo_url) {
                    Storage::disk('public')->delete($company->logo_url);
                }
                $company->logo_url = $request->file('logo')->store('companies/logos', 'public');
                $company->save();
            }

            if ($request->hasFile('cover')) {
                // Delete old
                if ($company->cover_image_url) {
                    Storage::disk('public')->delete($company->cover_image_url);
                }
                $company->cover_image_url = $request->file('cover')->store('companies/covers', 'public');
                $company->save();
            }

            /* --------------------
             | Pivot relations
             -------------------- */
            if ($request->categories) {
                $company->categories()->sync($request->categories);
            }

            if ($request->stack_ids) {
                $company->stacks()->sync($request->stack_ids);
            }

            /* --------------------
             | Dynamic Relations (Branches & Projects)
             -------------------- */
            // Strategy: Delete all and recreate since we don't have IDs to map
            if ($request->has('branches')) {
                $validBranches = [];
                foreach ($request->branches as $branch) {
                    // ensure all fields for this branch are filled (not null or empty)
                    $allFilled = true;
                    foreach ($branch as $value) {
                        if ($value === null || (is_string($value) && trim($value) === '')) {
                            $allFilled = false;
                            break;
                        }
                    }
                    if ($allFilled) {
                        $validBranches[] = $branch;
                    }
                }

                if (count($validBranches)) {
                    $company->branches()->delete();
                    $company->branches()->createMany($validBranches);
                }
            }

            if ($request->has('projects')) {
                $validProjects = [];
                foreach ($request->projects as $project) {
                    // ensure all fields for this project are filled (not null or empty)
                    $allFilled = true;
                    foreach ($project as $value) {
                        if ($value === null || (is_string($value) && trim($value) === '')) {
                            $allFilled = false;
                            break;
                        }
                    }
                    if ($allFilled) {
                        $validProjects[] = $project;
                    }
                }

                if (count($validProjects)) {
                    $company->projects()->delete();
                    $company->projects()->createMany($validProjects);
                }
            }
            
            // Handle Gallery (Append new ones)
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $path = $file->store('companies/gallery', 'public');
                    $company->gallery()->create(['image_url' => $path]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.companies.index')
                ->with('success', 'Company updated successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Something went wrong while updating the company. ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $company = Company::findOrFail($id);

        if ($company->logo_url) {
            Storage::disk('public')->delete($company->logo_url);
        }
        if ($company->cover_image_url) {
            Storage::disk('public')->delete($company->cover_image_url);
        }
        $company->delete();
        return redirect()->route('admin.companies.index')->with('success', 'Company deleted successfully.');
    }
}
