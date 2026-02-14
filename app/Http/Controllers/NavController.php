<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class NavController extends Controller
{
    //
    public function index()
    {
        $parentCategories = Category::where('parent_id', 0)->orderBy('name', 'asc')->get();
        return view('partials.navbar', compact('parentCategories'));
    }
}
