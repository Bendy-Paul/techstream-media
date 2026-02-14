<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Category;

class StateController extends Controller
{
    public function index(){
        $states = State::all();
        $categories = Category::all();
        return view('public.all-states', compact('states', 'categories'));
    }
    //
    public function show($slug){
    $state = State::where('slug', $slug)->firstOrFail();
    $all_states = State::all();
    $categories = Category::all();

    return view('public.state', compact('state', 'categories', 'all_states'));

    }


}
