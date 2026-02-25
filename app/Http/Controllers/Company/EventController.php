<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\City;
use App\Models\Tag;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class EventController extends Controller
{
    public function index()
    {
        $company = Auth::user()->company;
        // Get events where the company is an organizer or co-organizer
        $events = Event::where('organizer_id', Auth::user()->organizer?->id)
            ->latest()
            ->paginate(10);

        return view('company.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::where('type', 'event')->get();
        $cities     = City::orderBy('name')->get();
        $tags       = Tag::all();

        return view('company.events.create', compact('categories', 'cities', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'start_datetime' => 'required|date',
            'end_datetime'   => 'required|date|after:start_datetime',
            'location_name'  => 'nullable|string|max:255',
            'city_id'        => 'nullable|exists:cities,id',
            'is_virtual'     => 'boolean',
            'price'          => 'nullable|numeric|min:0',
            'ticket_url'     => 'nullable|url',
            'categories'     => 'nullable|array',
            'categories.*'   => 'exists:categories,id',
            'tags'           => 'nullable|array',
            'tags.*'         => 'exists:tags,id',
            'banner_image_upload' => 'nullable|image|max:4096',
        ]);

        $user    = Auth::user();
        $company = $user->company;

        // Find or create an organizer record for this company
        $organizer = $company->organizers()->first();
        if (!$organizer) {
            $organizer = $company->organizers()->create([
                'name'        => $company->name,
                'slug'        => Str::slug($company->name . '-' . uniqid()),
                'email'       => $company->email,
                'description' => $company->description,
                'logo_path'   => $company->logo_url,
            ]);
        }

        $bannerPath = null;
        if ($request->hasFile('banner_image_upload')) {
            $bannerPath = $request->file('banner_image_upload')->store('events/banners', 'public');
        }

        $event = Event::create([
            'organizer_id'    => $organizer->id,
            'title'           => $validated['title'],
            'slug'            => Str::slug($validated['title']) . '-' . uniqid(),
            'description'     => $validated['description'],
            'start_datetime'  => $validated['start_datetime'],
            'end_datetime'    => $validated['end_datetime'],
            'location_name'   => $validated['location_name'] ?? null,
            'city_id'         => $validated['city_id'] ?? null,
            'is_virtual'      => $request->boolean('is_virtual'),
            'price'           => $validated['price'] ?? 0,
            'ticket_url'      => $validated['ticket_url'] ?? null,
            'banner_image_url' => $bannerPath,
            'event_status'    => 'pending',
        ]);

        if (!empty($validated['categories'])) {
            $event->categories()->sync($validated['categories']);
        }
        if (!empty($validated['tags'])) {
            $event->tags()->sync($validated['tags']);
        }

        return redirect()->route('company.events.index')->with('success', 'Event submitted for review.');
    }

    public function edit(Event $event)
    {
        $this->authorizeEvent($event);

        $categories     = Category::where('type', 'event')->get();
        $cities         = City::orderBy('name')->get();
        $tags           = Tag::all();
        $selectedCats   = $event->categories->pluck('id')->toArray();
        $selectedTags   = $event->tags->pluck('id')->toArray();

        return view('company.events.edit', compact('event', 'categories', 'cities', 'tags', 'selectedCats', 'selectedTags'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeEvent($event);

        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'start_datetime' => 'required|date',
            'end_datetime'   => 'required|date|after:start_datetime',
            'location_name'  => 'nullable|string|max:255',
            'city_id'        => 'nullable|exists:cities,id',
            'is_virtual'     => 'boolean',
            'price'          => 'nullable|numeric|min:0',
            'ticket_url'     => 'nullable|url',
            'categories'     => 'nullable|array',
            'categories.*'   => 'exists:categories,id',
            'tags'           => 'nullable|array',
            'tags.*'         => 'exists:tags,id',
            'banner_image_upload' => 'nullable|image|max:4096',
        ]);

        $bannerPath = $event->banner_image_url;
        if ($request->hasFile('banner_image_upload')) {
            if ($bannerPath && Storage::disk('public')->exists($bannerPath)) {
                Storage::disk('public')->delete($bannerPath);
            }
            $bannerPath = $request->file('banner_image_upload')->store('events/banners', 'public');
        }

        $event->update([
            'title'           => $validated['title'],
            'description'     => $validated['description'],
            'start_datetime'  => $validated['start_datetime'],
            'end_datetime'    => $validated['end_datetime'],
            'location_name'   => $validated['location_name'] ?? null,
            'city_id'         => $validated['city_id'] ?? null,
            'is_virtual'      => $request->boolean('is_virtual'),
            'price'           => $validated['price'] ?? 0,
            'ticket_url'      => $validated['ticket_url'] ?? null,
            'banner_image_url' => $bannerPath,
        ]);

        $event->categories()->sync($validated['categories'] ?? []);
        $event->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('company.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $this->authorizeEvent($event);

        if ($event->banner_image_url && Storage::disk('public')->exists($event->banner_image_url)) {
            Storage::disk('public')->delete($event->banner_image_url);
        }

        $event->delete();

        return redirect()->route('company.events.index')->with('success', 'Event deleted successfully.');
    }

    private function authorizeEvent(Event $event): void
    {
        $organizer = Auth::user()->company?->organizers()->first();
        if (!$organizer || $event->organizer_id !== $organizer->id) {
            abort(403);
        }
    }
}
