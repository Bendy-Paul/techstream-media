<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Job;
use App\Models\Company;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $resumeCount = $user->resumes()->count();
        $applicationsCount = $user->applications()->count();
        $resumeLimit = $user->resume_limit;
        $savedItemsCount = $user->savedItems()->count();
        $activities = $user->activities()->latest()->take(10)->get();

        return view('user.dashboard', compact('resumeCount', 'applicationsCount', 'resumeLimit', 'savedItemsCount', 'activities'));
    }

    public function savedItems()
    {
        $user = auth()->user();
        
        $savedJobs = $user->savedItems()
            ->where('item_type', Job::class)
            ->with('item.company', 'item.city') // Eager load relationships
            ->get()
            ->pluck('item')
            ->filter(); // Filter out nulls in case item was deleted

        $savedCompanies = $user->savedItems()
            ->where('item_type', Company::class)
            ->with('item.city')
            ->get()
            ->pluck('item')
            ->filter();

        $savedEvents = $user->savedItems()
            ->where('item_type', Event::class)
            ->with('item.city')
            ->get()
            ->pluck('item')
            ->filter();

        return view('user.saved-items', compact('savedJobs', 'savedCompanies', 'savedEvents'));
    }

    public function applications()
    {
        $user = auth()->user();
        // $slot 
        $applications = $user->applications;
        return view('user.applications.index', compact('applications'));
    }

    public function settings()
    {
        return view('user.settings');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'phone_number' => 'string|max:255|unique:users,phone_number,' . auth()->id(),
            'current_password' => ['required', 'current_password'],
        ]);

        auth()->user()->update($request->only('name', 'email', 'phone_number'));

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        auth()->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function updateSubscriptions(Request $request)
    {
        $user = auth()->user();
        $user->is_subscribed_newsletter = $request->has('is_subscribed_newsletter');
        $user->is_subscribed_job_board = $request->has('is_subscribed_job_board');
        $user->save();

        return back()->with('success', 'Subscriptions updated successfully.');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        \Illuminate\Support\Facades\Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been deleted.');
    }
}
