<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['company', 'user'])->latest()->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function edit($id)
    {
        $review = Review::with(['company', 'user'])->findOrFail($id);
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);
        $review->update($request->only(['rating', 'comment', 'status']));
        return redirect()->route('admin.reviews.index')->with('success', 'Review updated successfully.');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }
}
