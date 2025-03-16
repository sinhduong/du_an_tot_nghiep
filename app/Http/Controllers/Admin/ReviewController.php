<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::with(['user', 'room'])->latest()->get();
        return view('admins.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        return view('admins.reviews.show', compact('review'));
    }

    public function response(Request $request, Review $review)
    {
        $request->validate(['response' => 'required|string']);
        $review->update(['response' => $request->response]);

        try {
            return redirect()
                ->route('admin.reviews.index')
                ->with('success', 'Phản hồi đã được cập nhật.');
        } catch (\Throwable $th) {

            return back()
                ->with('success', true)
                ->with('error', $th->getMessage());
        }
    }



    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Đánh giá đã bị xóa.');
    }
}
