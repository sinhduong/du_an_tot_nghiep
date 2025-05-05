<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:reviews_list')->only(['index']);
        $this->middleware('permission:reviews_detail')->only(['show']);
        $this->middleware('permission:reviews_response')->only(['response']);
        $this->middleware('permission:reviews_delete')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title="Danh sách đánh giá";
        $reviews = Review::with(['user', 'booking'])->latest()->get();
        return view('admins.reviews.index', compact('reviews','title'));
    }

    /**
     * Show the specified review.
     */
    public function show(Review $review)
    {
        $review->load(['user', 'booking']);
        return view('admins.reviews.show', compact('review'));
    }

    /**
     * Respond to a review.
     */
    public function response(Request $request, Review $review)
    {
        $request->validate(['response' => 'required|string']);

        try {
            $review->update(['response' => $request->response]);
            return redirect()
                ->route('admin.reviews.index')
                ->with('success', 'Phản hồi đã được cập nhật.');
        } catch (\Throwable $th) {
            return back()
                ->with('error', 'Đã có lỗi xảy ra: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review)
    {
        try {
            $review->delete();
            return redirect()
                ->route('admin.reviews.index')
                ->with('success', 'Đánh giá đã bị xóa.');
        } catch (\Throwable $th) {
            return back()
                ->with('error', 'Đã có lỗi xảy ra: ' . $th->getMessage());
        }
    }
}
