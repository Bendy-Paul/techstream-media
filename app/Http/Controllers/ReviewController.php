<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|integer',
            'item_type' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Optional: Check if user already reviewed this item
        $existing = Review::where('user_id', Auth::id())
            ->where('item_id', $validated['item_id'])
            ->where('item_type', $validated['item_type'])
            ->first();

        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already reviewed this item.'
            ], 422);
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'item_id' => $validated['item_id'],
            'item_type' => $validated['item_type'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => true, // Auto-approve or set to false based on policy
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Review submitted successfully.',
            'review' => $review
        ]);
    }
}
