<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\City;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $company = $user->company;
        $cities = City::all();

        return view('company.profile.edit', compact('company', 'cities'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website_url' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:255',
            'city_id' => 'nullable|exists:cities,id',
            'description' => 'required|string',
            'tagline' => 'nullable|string|max:255',
            'logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo_url')) {
            $validatedData['logo_url'] = '/storage/' . $request->file('logo_url')->store('companies/logos', 'public');
        } else {
            unset($validatedData['logo_url']);
        }

        if ($request->hasFile('cover_image_url')) {
            $validatedData['cover_image_url'] = '/storage/' . $request->file('cover_image_url')->store('companies/covers', 'public');
        } else {
            unset($validatedData['cover_image_url']);
        }

        if ($user->company) {
            $user->company->update($validatedData);
        } else {
            $validatedData['user_id'] = $user->id;
            $validatedData['slug'] = \Illuminate\Support\Str::slug($validatedData['name'] . '-' . uniqid());
            $validatedData['is_verified'] = false; // Initially unverified
            // Add other default fields
            Company::create($validatedData);
        }

        return redirect()->route('company.profile.edit')->with('success', 'Company profile updated successfully.');
    }
}
