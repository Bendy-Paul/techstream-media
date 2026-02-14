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

                // 3. Create the Main Event
                // We use except() to remove fields that don't belong in the events table
                $event = Event::create([
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
                $event->organizers()->sync($request->organizers);
                $event->partners()->sync($request->partners);
                $event->tags()->sync($request->tags);

                // 5. Handle Dynamic Speakers
                if ($request->has('speakers') && $request->speakers->name != null) {
                    foreach ($request->speakers as $index => $speakerData) {
                        $speaker = $event->speakers()->create([
                            'name' => $speakerData['name'],
                            'position' => $speakerData['position'],
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

                return redirect()->route('admin.events')
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
        $event = Event::with(['categories', 'organizers', 'partners', 'tags', 'speakers', 'galleries'])->findOrFail($id);
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
                   // $bannerPath = 'storage/' . $path; // Consistent with article fix? 
                   // Looking at store method, it saves relative path. The view handles 'storage/'.
                   // Actually, ArticleController store saved relative path, but I changed it to 'storage/'.
                   // EventController store saves relative path. Let's keep consistent with existing EventController store.
                   $bannerPath = $path; // The view should handle asset('storage/'.$path)
                }

                // 2. Update Main Event
                $event->update([
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
                $event->organizers()->sync($request->input('organizers', []));
                $event->partners()->sync($request->input('partners', []));
                $event->tags()->sync($request->input('tags', []));

                // 4. Handle Speakers (This is tricky - ideally we sync or update existing)
                // For simplicity, we might clear and recreate, OR try to update if ID exists.
                // The create form sends array of speakers. Edit form needs to handle this.
                // Current implementation in store creates new.
                // strategy: Delete all and recreate? Or update?
                // Given the complex nature (images involved), delete and recreate is easiest but loses old images if not careful.
                // Better strategy:
                // - If ID is present, update.
                // - If ID is NOT present, create.
                // - If ID is missing from request but exists in DB, delete.
                // However, the request structure in `store` was just an array of data.
                
                // Let's implement a wipe-and-replace for speakers for simplicity consistent with the request, 
                // BUT we must preserve images if not re-uploaded. 
                // Actually, let's keep it simple: clear old speakers and create new ones is destructive to images not re-uploaded.
                // Correct approach:
                // The user needs to re-enter speakers? No, that's bad UX.
                // The edit view should list existing speakers.
                
                // Let's do a simple full replacement for now, as managing partial updates for nested dynamic forms is complex without JS logic.
                // NOTE: Proper implementation requires JS to track IDs. 
                // I will delete old speakers and recreate them. 
                // LIMITATION: User must re-upload speaker images if they edit speakers. 
                // TO IMPROVE: Check if we can keep images.
                
                $event->speakers()->delete(); // This deletes records. Images remain in storage (orphaned).
                
                if ($request->has('speakers')) {
                    foreach ($request->speakers as $index => $speakerData) {
                         // Skip empty rows
                        if(empty($speakerData['name'])) continue;

                        $imgPath = null;
                        // Logic to preserve image if we had hidden input? 
                        // For now, new speakers or re-added speakers need image upload.
                        if ($request->hasFile("speaker_images.$index")) {
                             $imgPath = $request->file("speaker_images.$index")->store('events/speakers', 'public');
                        } elseif (isset($speakerData['existing_image'])) {
                            $imgPath = $speakerData['existing_image'];
                        }

                        $event->speakers()->create([
                            'name' => $speakerData['name'],
                            'position' => $speakerData['position'],
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