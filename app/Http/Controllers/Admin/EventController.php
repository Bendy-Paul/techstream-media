<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\Company;
use App\Models\City;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\StoreEventRequest;
use App\Http\Requests\Admin\UpdateEventRequest;
use App\Models\Organizer;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $events = Event::all();
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::where('type', 'event')->get();
        $companies = Company::all();
        $tags = Tag::all();
        $cities = City::all();

        return view('admin.events.create', compact('categories', 'companies', 'tags', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        // 1. Get the validated data from your Request class
        $data = $request->validated();

        try {
            $virtual = $request->input('is_virtual', false);
            // Start a Database Transaction to ensure data integrity
            return DB::transaction(function () use ($request, $data, $virtual) {

                // 2. Handle the Banner Image Upload
                if ($request->hasFile('banner_image_upload')) {
                    $data['banner_image'] = $request->file('banner_image_upload')->store('events/banners', 'public');
                }

                // Determine Organizer (Owner)
                $organizerId = null;
                $companyIds = $request->input('organizers', []);

                if (!empty($companyIds)) {
                    // Take the first company as the primary owner
                    $ownerCompanyId = $companyIds[0];
                    $company = Company::find($ownerCompanyId);

                    if ($company) {
                        // Find or Create Organizer Profile for this Company
                        // We assume one profile per company for now, or use the first one found.
                        $organizer = $company->organizers()->first();

                        if (!$organizer) {
                            $organizer = $company->organizers()->create([
                                'name' => $company->name,
                                'slug' => Str::slug($company->name . '-organizer-' . uniqid()), // Ensure unique slug
                                'email' => $company->email,
                                'description' => $company->description,
                                'logo_path' => $company->logo_url, // Assuming compatible or null
                            ]);
                        }
                        $organizerId = $organizer->id;
                    }
                }

                // 3. Create the Main Event
                $event = Event::create([
                    'organizer_id' => $organizerId, // Set the owner
                    'title' => $request->title,
                    'slug' => Str::slug($request->title),
                    'description' => $request->description,
                    'location_name' => $request->location_name,
                    'city_id' => $request->city_id,
                    'is_virtual' => $virtual,
                    'start_datetime' => $request->start_datetime,
                    'end_datetime' => $request->end_datetime,
                    'banner_image_url' => $data['banner_image'] ?? null,
                ]);

                // 4. Attach Many-to-Many Relationships
                $event->categories()->sync($request->categories);
                // Use coOrganizers to sync the companies as generic partners/co-hosts
                // We sync ALL selected companies, including the owner, to the pivot table for backward compatibility/display
                $event->coOrganizers()->sync($request->organizers);
                $event->partners()->sync($request->partners);
                $event->tags()->sync($request->tags);

                // 5. Handle Dynamic Speakers
                if ($request->has('speakers') && is_array($request->speakers)) {
                    foreach ($request->speakers as $index => $speakerData) {
                        if (empty($speakerData['name'])) continue; // Skip empty

                        $speaker = $event->speakers()->create([
                            'name' => $speakerData['name'],
                            'position' => $speakerData['position'] ?? null,
                        ]);

                        // Check if this specific speaker has an image
                        if ($request->hasFile("speaker_images.$index")) {
                            $imgPath = $request->file("speaker_images.$index")->store('events/speakers', 'public');
                            $speaker->update(['image_path' => $imgPath]);
                        }
                    }
                }

                // 6. Handle Gallery Uploads
                if ($request->hasFile('gallery')) {
                    foreach ($request->file('gallery') as $image) {
                        $path = $image->store('events/gallery', 'public');
                        $event->gallery()->create(['image_path' => $path]);
                    }
                }

                return redirect()->route('admin.events.index')
                    ->with('success', 'Event created successfully!');
            });
        } catch (\Exception $e) {
            // If anything fails, Laravel will automatically roll back the database 
            // because of the DB::transaction
            return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
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
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Eager load coOrganizers (which currently serves as the list of attached companies)
        $event = Event::with(['categories', 'coOrganizers', 'partners', 'tags', 'speakers', 'galleries'])->findOrFail($id);
        $categories = Category::where('type', 'event')->get();
        $companies = Company::all();
        $tags = Tag::all();
        $cities = City::all();

        return view('admin.events.edit', compact('event', 'categories', 'companies', 'tags', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, $id)
    {
        $event = Event::findOrFail($id);
        $data = $request->validated();

        try {
            $virtual = $request->input('is_virtual', false);

            DB::transaction(function () use ($request, $event, $data, $virtual) {

                // 1. Handle Banner Image
                $bannerPath = $event->banner_image_url;
                if ($request->hasFile('banner_image_upload')) {
                    // Start: Fix for legacy/new path consistency
                    if ($event->banner_image_url && Storage::disk('public')->exists($event->banner_image_url)) {
                        // Storage::disk('public')->delete($event->banner_image_url);
                    }
                    $path = $request->file('banner_image_upload')->store('events/banners', 'public');
                    $bannerPath = $path;
                }

                // Determine Organizer (Owner)
                $organizerId = $event->organizer_id; // Keep existing by default
                $companyIds = $request->input('organizers', []);

                // If the submitted organizers list is not empty, check if we need to update the owner
                if (!empty($companyIds)) {
                    // Take the first company as the primary owner
                    $ownerCompanyId = $companyIds[0];
                    $company = Company::find($ownerCompanyId);

                    if ($company) {
                        // Find or Create Organizer Profile
                        $organizer = $company->organizers()->first();

                        if (!$organizer) {
                            $organizer = $company->organizers()->create([
                                'name' => $company->name,
                                'slug' => Str::slug($company->name . '-organizer-' . uniqid()),
                                'email' => $company->email,
                                'description' => $company->description,
                                'logo_path' => $company->logo_url,
                            ]);
                        }
                        $organizerId = $organizer->id;
                    }
                }

                // 2. Update Main Event
                $event->update([
                    'organizer_id' => $organizerId,
                    'title' => $request->title,
                    'slug' => Str::slug($request->title),
                    'description' => $request->description,
                    'location_name' => $request->location_name,
                    'city_id' => $request->city_id,
                    'is_virtual' => $virtual,
                    'start_datetime' => $request->start_datetime,
                    'end_datetime' => $request->end_datetime,
                    'banner_image_url' => $bannerPath,
                ]);

                // 3. Sync Relationships
                $event->categories()->sync($request->input('categories', []));
                $event->coOrganizers()->sync($request->input('organizers', [])); // Synced as pivot
                $event->partners()->sync($request->input('partners', []));
                $event->tags()->sync($request->input('tags', []));

                // 4. Handle Speakers
                $event->speakers()->delete(); // Warning: Destructive to existing speakers

                if ($request->has('speakers') && is_array($request->speakers)) {
                    foreach ($request->speakers as $index => $speakerData) {
                        // Skip empty rows
                        if (empty($speakerData['name'])) continue;

                        $imgPath = null;
                        // Logic to preserve image if we had hidden input? 
                        if ($request->hasFile("speaker_images.$index")) {
                            $imgPath = $request->file("speaker_images.$index")->store('events/speakers', 'public');
                        } elseif (isset($speakerData['existing_image'])) {
                            $imgPath = $speakerData['existing_image'];
                        }

                        $event->speakers()->create([
                            'name' => $speakerData['name'],
                            'position' => $speakerData['position'] ?? null,
                            'image_path' => $imgPath
                        ]);
                    }
                }

                // 5. Handle Gallery (Append new ones)
                if ($request->hasFile('gallery')) {
                    foreach ($request->file('gallery') as $image) {
                        $path = $image->store('events/gallery', 'public');
                        $event->gallery()->create(['image_path' => $path]);
                    }
                }

                // Handle Gallery Deletion (if requested)
                if ($request->has('delete_gallery_ids')) {
                    $event->gallery()->whereIn('id', $request->delete_gallery_ids)->delete();
                }
            });

            return redirect()->route('admin.events.index')
                ->with('success', 'Event updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $event = Event::findOrFail($id);
        // Delete associated images from storage
        if ($event->banner_image_url) {
            Storage::disk('public')->delete($event->banner_image_url);
        }
        // Delete associated gallery images
        foreach ($event->galleries as $gallery) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        // Delete associated speaker images
        foreach ($event->speakers as $speaker) {
            if ($speaker->image_path) {
                Storage::disk('public')->delete($speaker->image_path);
            }
        }
        // Finally, delete the event itself
        $event->delete();
        return redirect()->route('admin.events')->with('success', 'Event deleted successfully!');
    }
}
