<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'booking'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('clients.reviews.index', compact('reviews'));
    }

    public function store(Request $request)
    {

        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', Auth::id())
            ->where('status', 'check_out')
            ->whereNotNull('actual_check_out')
            ->first();

        if (!$booking) {
            return back()->with('error', 'Không thể đánh giá đơn đặt phòng này.');
        }

        // Check if review already exists
        if ($booking->review) {
            return back()->with('error', 'Bạn đã đánh giá đơn đặt phòng này.');
        }

        // Create review
        Review::create([
            'user_id' => Auth::id(),
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá!');
    }
}
