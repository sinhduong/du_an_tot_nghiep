<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Http\Requests\StorebookingRequest;
use App\Http\Requests\UpdatebookingRequest;
use App\Models\Blogs;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Đơn đặt phòng mới nhất';
        $bookings = Booking::with('user', 'rooms')->latest()->paginate(10);
        return view('admins.bookings.index', compact('bookings', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.bookings.create');
    }

    public function store(StoreBookingRequest $request)
    {
        Booking::create($request->validated());
        return redirect()->route('bookings.index')->with('success', 'Đã thêm đơn đặt phòng.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = Booking::with('user', 'rooms', 'payments')->findOrFail($id);
        $title = 'Chi tiết đơn đặt phòng';
        return view('admins.bookings.show', compact('title', 'booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $bookings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $currentStatus = $booking->status;
        $newStatus = $request->status;

        // Kiểm tra điều kiện hợp lệ để cập nhật trạng thái
        if ($currentStatus === 'pending_confirmation') {
            // Nếu trạng thái hiện tại là "Chưa xác nhận", cho phép đổi sang bất kỳ trạng thái nào
            $booking->update(['status' => $newStatus]);
            return redirect()->route('admin.bookings.index')->with('success', 'Cập nhật trạng thái đặt phòng thành công.');
        } elseif ($currentStatus === 'confirmed' && in_array($newStatus, ['paid', 'cancelled'])) {
            // Nếu trạng thái hiện tại là "Đã xác nhận", chỉ cho phép đổi sang "Đã thanh toán" hoặc "Đã hủy"
            $booking->update(['status' => $newStatus]);
            return redirect()->route('admin.bookings.index')->with('success', 'Cập nhật trạng thái đặt phòng thành công.');
        } elseif ($currentStatus === 'paid' && in_array($newStatus, ['check_in', 'cancelled', 'refunded'])) {
            // Nếu trạng thái hiện tại là "Đã thanh toán", chỉ cho phép đổi sang "Đã check in", "Đã hủy", hoặc "Đã hoàn tiền"
            $booking->update(['status' => $newStatus]);
            return redirect()->route('admin.bookings.index')->with('success', 'Cập nhật trạng thái đặt phòng thành công.');
        } elseif ($currentStatus === 'check_in' && $newStatus === 'check_out') {
            // Nếu trạng thái hiện tại là "Đã check in", chỉ cho phép đổi sang "Đã checkout"
            $booking->update(['status' => $newStatus]);
            return redirect()->route('admin.bookings.index')->with('success', 'Cập nhật trạng thái đặt phòng thành công.');
        }

        // Nếu trạng thái mới không hợp lệ, trả về lỗi
        return redirect()->back()->with('error', 'Không thể thay đổi trạng thái từ ' . $currentStatus . ' sang ' . $newStatus . '.');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $bookings)
    {
        //
    }
}
