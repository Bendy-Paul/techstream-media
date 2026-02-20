<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('organizer_id', auth()->id())->latest()->paginate(10);
        return view('company.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('company.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            // details like start_date, end_date
        ]);

        $validated['organizer_id'] = auth()->id();
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title'] . '-' . uniqid());
        $validated['status'] = 'draft';

        Event::create($validated);

        return redirect()->route('company.events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }
        $categories = Category::all();
        return view('company.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $event->update($validated);
        return redirect()->route('company.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }
        $event->delete();
        return redirect()->route('company.events.index')->with('success', 'Event deleted successfully.');
    }
}
