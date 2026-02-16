<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // For now, just return all activities for the authenticated user or all for admin
        // This is a placeholder implementation
        if (auth()->user()->role === 'admin') {
            $activities = Activity::with('subject', 'user')->latest()->paginate(20);
        } else {
            $activities = auth()->user()->activities()->with('subject')->latest()->paginate(20);
        }

        return response()->json($activities);
    }
}
