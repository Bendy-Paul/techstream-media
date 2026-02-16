<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\Article;
use App\Models\Event;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $totalUsers = User::count();
        $totalCompanies = Company::count();
        $totalArticles = Article::count();
        $totalevents = Event::count();
        $totalevents = Event::count();
        $companies = Company::limit(5)->get();
        // Return activities for the logged-in admin
        $activities = auth()->user()->activities()->latest()->take(10)->get();
        
        return view('admin.dashboard', compact('totalUsers', 'totalCompanies', 'totalArticles', 'totalevents', 'companies', 'activities'));
    }
}
