<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organizer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrganizerController extends Controller
{
    /**
     * Display the organizer dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        // Assuming user has one primary organizer profile for now
        $organizer = $user->organizers()->first();

        if (!$organizer) {
            return redirect()->route('user.organizer.create');
        }

        return view('user.organizer.dashboard', compact('organizer'));
    }

    /**
     * Show the form for creating a new organizer profile.
     */
    public function create()
    {
        // If already has one, redirect to dashboard (or allow multiple if design permits)
        if (Auth::user()->organizers()->exists()) {
            return redirect()->route('user.organizer.index');
        }

        return view('user.organizer.create');
    }

    /**
     * Store a newly created organizer profile in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'website', 'phone', 'description']);
        $data['slug'] = Str::slug($request->name . '-' . uniqid());

        if ($request->hasFile('logo')) {
            // $data['logo_path'] = $request->file('logo')->store('organizers/logos', 'public');
        }

        Auth::user()->organizers()->create($data);

        return redirect()->route('user.organizer.index')->with('success', 'Organizer profile created successfully!');
    }

    /**
     * Show the form for editing the organizer profile.
     */
    public function edit()
    {
        $organizer = Auth::user()->organizers()->firstOrFail();
        return view('user.organizer.edit', compact('organizer'));
    }

    /**
     * Update the organizer profile in storage.
     */
    public function update(Request $request)
    {
        $organizer = Auth::user()->organizers()->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'website', 'phone', 'description']);
        // Slug update logic if needed, usually better to keep stable or allow explicit change
        // $data['slug'] = Str::slug($request->name); 

        if ($request->hasFile('logo')) {
            // $data['logo_path'] = $request->file('logo')->store('organizers/logos', 'public');
        }

        $organizer->update($data);

        return redirect()->route('user.organizer.index')->with('success', 'Profile updated successfully!');
    }
}
