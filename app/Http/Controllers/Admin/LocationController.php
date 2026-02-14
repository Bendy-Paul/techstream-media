<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Country;
use App\Models\State;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        return view('admin.locations.index', compact('countries', 'states', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $type = $request->input('type');
        $name = $request->input('name');
        $slug = strtolower(str_replace(' ', '-', $name));
        $country_shortcode = $request->input('country_shortcode');
        $country_phonecode = $request->input('country_phonecode');
        $country_id = $request->input('country_id');

        $statecode = $request->input('statecode');
        $state_id = $request->input('state_id');  
        
        $city_code = $request->input('city_code');

        if ($type === 'country') {
            Country::create(['name' => $name, 'sortname' => $country_shortcode, 'phonecode' => $country_phonecode]);
        } elseif ($type === 'state') {
            State::create(['name' => $name, 'country_id' => $country_id, 'state_code' => $statecode]);
        } elseif ($type === 'city') {
            City::create(['name' => $name, 'state_id' => $state_id, 'city_code' => $city_code]);
        }

        return redirect()->back();
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $type = $request->input('type');
        $id = $request->input('id');
        if ($type === 'country') {
            Country::destroy($id);
        } elseif ($type === 'state') {
            State::destroy($id);
        } elseif ($type === 'city') {
            City::destroy($id);
        }

        return redirect()->back()->with('status', ucfirst($type) . ' deleted successfully.');
    }
}
