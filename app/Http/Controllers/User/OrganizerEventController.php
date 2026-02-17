<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\City;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrganizerEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizer = Auth::user()->organizers()->first();

        if (!$organizer) {
            return redirect()->route('user.organizer.create');
        }

        $events = $organizer->events()->latest()->paginate(10);

        return view('user.organizer.events.index', compact('events', 'organizer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizer = Auth::user()->organizers()->first();
        if (!$organizer) {
            return redirect()->route('user.organizer.create');
        }

        $categories = Category::where('type', 'event')->get();
        $tags = Tag::all();
        $cities = City::all();

        return view('user.organizer.events.create', compact('categories', 'tags', 'cities', 'organizer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $organizer = Auth::user()->organizers()->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'description' => 'required|string',
            'banner_image_upload' => 'nullable|image|max:2048',
            // Add other validations as needed
        ]);

        try {
            DB::transaction(function () use ($request, $organizer) {
                // 1. Handle Banner Image
                $bannerPath = null;
                if ($request->hasFile('banner_image_upload')) {
                    $bannerPath = $request->file('banner_image_upload')->store('events/banners', 'public');
                }

                // 2. Create Event
                $event = $organizer->events()->create([
                    'title' => $request->title,
                    'slug' => Str::slug($request->title . '-' . uniqid()),
                    'description' => $request->description,
                    'location_name' => $request->location_name,
                    'city_id' => $request->city_id,
                    'is_virtual' => $request->has('is_virtual'),
                    'start_datetime' => $request->start_datetime,
                    'end_datetime' => $request->end_datetime,
                    'banner_image_url' => $bannerPath,
                    'price' => $request->price ?? 0,
                    'ticket_url' => $request->ticket_url,
                    'is_featured' => false, // Users cannot feature their own events directly usually
                    'event_status' => Event::STATUS_PENDING, // Default to pending
                ]);

                // 3. Sync Relationships
                $event->categories()->sync($request->input('categories', []));
                $event->tags()->sync($request->input('tags', []));

                // 4. Handle Speakers (Simplified for now)
                if ($request->has('speakers') && is_array($request->speakers)) {
                    foreach ($request->speakers as $index => $speakerData) {
                        if (empty($speakerData['name'])) continue;

                        $imgPath = null;
                        if ($request->hasFile("speaker_images.$index")) {
                            $imgPath = $request->file("speaker_images.$index")->store('events/speakers', 'public');
                        }

                        $event->speakers()->create([
                            'name' => $speakerData['name'],
                            'position' => $speakerData['position'] ?? null,
                            'image_path' => $imgPath
                        ]);
                    }
                }
            });

            return redirect()->route('user.organizer.events.index')
                ->with('success', 'Event submitted successfully! It is pending approval.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating event: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $organizer = Auth::user()->organizers()->firstOrFail();
        $event = $organizer->events()->with(['categories', 'tags', 'speakers'])->findOrFail($id);

        $categories = Category::where('type', 'event')->get();
        $tags = Tag::all();
        $cities = City::all();

        return view('user.organizer.events.edit', compact('event', 'categories', 'tags', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $organizer = Auth::user()->organizers()->firstOrFail();
        $event = $organizer->events()->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'description' => 'required|string',
            'banner_image_upload' => 'nullable|image|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request, $event) {
                // 1. Handle Banner
                $bannerPath = $event->banner_image_url;
                if ($request->hasFile('banner_image_upload')) {
                    $bannerPath = $request->file('banner_image_upload')->store('events/banners', 'public');
                }

                $event->update([
                    'title' => $request->title,
                    // 'slug' => Str::slug($request->title), // Keep slug stable or update? Let's keep stable to avoid breaking links
                    'description' => $request->description,
                    'location_name' => $request->location_name,
                    'city_id' => $request->city_id,
                    'is_virtual' => $request->has('is_virtual'),
                    'start_datetime' => $request->start_datetime,
                    'end_datetime' => $request->end_datetime,
                    'banner_image_url' => $bannerPath,
                    'price' => $request->price ?? 0,
                    'ticket_url' => $request->ticket_url,
                    // 'event_status' => Event::STATUS_PENDING, // Should we reset to pending on update? Usually yes for critical changes.
                    // For now, let's reset to PENDING if it was published, to re-approve changes.
                    'event_status' => Event::STATUS_PENDING,
                ]);

                // 2. Sync Relationships
                $event->categories()->sync($request->input('categories', []));
                $event->tags()->sync($request->input('tags', []));

                // 3. Speakers (Wipe and recreate for simplicity as per Admin controller)
                $event->speakers()->delete();
                if ($request->has('speakers') && is_array($request->speakers)) {
                    foreach ($request->speakers as $index => $speakerData) {
                        if (empty($speakerData['name'])) continue;

                        $imgPath = null;
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
            });

            return redirect()->route('user.organizer.events.index')
                ->with('success', 'Event updated and submitted for approval.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating event: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $organizer = Auth::user()->organizers()->firstOrFail();
        $event = $organizer->events()->findOrFail($id);

        $event->delete();

        return redirect()->route('user.organizer.events.index')
            ->with('success', 'Event deleted successfully.');
    }
}
