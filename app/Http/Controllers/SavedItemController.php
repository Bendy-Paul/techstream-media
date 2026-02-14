<?php

namespace App\Http\Controllers;

use App\Models\SavedItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedItemController extends Controller
{
    /**
     * Store a newly created resource in storage or remove it if exists.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|integer',
            'item_type' => 'required|string',
        ]);

        $user = Auth::user();

        // Check if already saved
        $existing = SavedItem::where('user_id', $user->id)
            ->where('item_id', $validated['item_id'])
            ->where('item_type', $validated['item_type'])
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'status' => 'removed',
                'message' => 'Item removed from saved list.',
                'item_id' => $validated['item_id'],
                'item_type' => $validated['item_type']
            ]);
        } else {
            SavedItem::create([
                'user_id' => $user->id,
                'item_id' => $validated['item_id'],
                'item_type' => $validated['item_type'],
            ]);

            return response()->json([
                'status' => 'saved',
                'message' => 'Item saved successfully.',
                'item_id' => $validated['item_id'],
                'item_type' => $validated['item_type']
            ]);
        }
    }
}
