<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;

class EventController extends Controller
{
    //
    public function index()
    {
        $events = Event::latest()->paginate(12);
        return view('public.events.events', compact('events'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $events = $category->events()->paginate(12); // Assuming relationship exists or will be fixed
        return view('public.events.events', compact('events', 'category'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        if ($event->is_featured) {
            return view('public.events.event-featured', compact('event'));
        }

        return view('public.events.event-detail', compact('event'));
    }
}
