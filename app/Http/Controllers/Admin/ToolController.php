<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tools;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tools = Tools::all();
        return view('admin.tools.index', compact('tools'));
        
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
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'icon_class' => 'nullable|string|max:255',
        ]);

        Tools::create([
            'name' => $request->input('name'),
            'category' => $request->input('category'),
            'icon_class' => $request->input('icon_class'),
        ]);

        return redirect()->route('admin.tools')->with('success', 'Tool added successfully.');
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
    public function destroy(Tools $tool)
    {
        //
        $tool->delete();
        return redirect()->route('admin.tools')->with('success', 'Tool deleted successfully.');
    }
}
