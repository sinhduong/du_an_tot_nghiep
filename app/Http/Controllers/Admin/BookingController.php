<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorebookingRequest;
use App\Http\Requests\UpdatebookingRequest;
use App\Models\Booking;
use App\Models\ServicePlus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    public function show($id)
    {
        $booking = Booking::with([
            'user',
            'rooms.roomType' => function ($query) {
                $query->with(['amenities', 'rulesAndRegulations', 'services']);
            },
            'rooms' => function ($query) {
                $query->withTrashed();
            },
            'servicePlus',
            'payments',
            'guests',
        ])->findOrFail($id);

        $title = 'Chi tiết đơn đặt phòng';
        $availableServicePlus = ServicePlus::where('is_active', 1)->get();
        // dd($booking);
        return view('admins.bookings.show', compact('title', 'booking', 'availableServicePlus'));
    }

    public function updateServicePlus($id, Request $request)
    {
        try {
            $booking = Booking::findOrFail($id);

            if ($request->has('action')) {
                // Thêm dịch vụ bổ sung
                if ($request->action === 'addServicePlus') {
                    Log::info('Processing addServicePlus', $request->all());

                    $request->validate([
                        'service_plus_id' => 'required|exists:service_plus,id',
                        'quantity' => 'required|integer|min:1',
                    ]);

                    try {
                        DB::beginTransaction();
                        $servicePlusId = $request->input('service_plus_id');
                        $quantity = $request->input('quantity');

                        Log::info("Checking if service_plus_id {$servicePlusId} exists for booking {$id}");

                        // Kiểm tra trùng lặp
                        if ($booking->servicePlus()->where('service_plus_id', $servicePlusId)->exists()) {
                            Log::warning("Service {$servicePlusId} already added to booking {$id}");
                            return response()->json([
                                'success' => false,
                                'message' => 'Dịch vụ này đã được thêm!',
                            ], 200); // Sử dụng status 200 thay vì 400 để tránh lỗi
                        }

                        $booking->servicePlus()->attach($servicePlusId, ['quantity' => $quantity]);
                        $servicePlus = ServicePlus::find($servicePlusId);

                        if (!$servicePlus) {
                            Log::error("ServicePlus with ID {$servicePlusId} not found");
                            throw new \Exception("Không tìm thấy dịch vụ bổ sung!");
                        }

                        DB::commit();

                        Log::info("ServicePlus {$servicePlusId} added to booking {$id} successfully");

                        return response()->json([
                            'success' => true,
                            'message' => 'Thêm dịch vụ thành công!',
                            'data' => [
                                'id' => $servicePlus->id,
                                'name' => $servicePlus->name,
                                'price' => $servicePlus->price,
                                'quantity' => $quantity,
                            ]
                        ]);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Error adding ServicePlus: ' . $e->getMessage(), ['exception' => $e]);
                        return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
                    }
                }

                // Cập nhật số lượng dịch vụ bổ sung
                if ($request->action === 'updateServicePlus') {
                    $request->validate([
                        'service_plus_id' => 'required|exists:service_plus,id',
                        'quantity' => 'required|integer|min:1',
                    ]);

                    try {
                        DB::beginTransaction();
                        $servicePlusId = $request->input('service_plus_id');
                        $quantity = $request->input('quantity');

                        $booking->servicePlus()->updateExistingPivot($servicePlusId, ['quantity' => $quantity]);
                        $servicePlus = ServicePlus::find($servicePlusId);
                        DB::commit();

                        return response()->json([
                            'success' => true,
                            'message' => 'Cập nhật số lượng thành công!',
                            'data' => [
                                'id' => $servicePlus->id,
                                'quantity' => $quantity,
                            ]
                        ]);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
                    }
                }

                // Xóa dịch vụ bổ sung
                if ($request->action === 'removeServicePlus') {
                    $request->validate([
                        'service_plus_id' => 'required|exists:service_plus,id',
                    ]);

                    try {
                        DB::beginTransaction();
                        $servicePlusId = $request->input('service_plus_id');
                        $booking->servicePlus()->detach($servicePlusId);
                        DB::commit();

                        return response()->json(['success' => true, 'message' => 'Xóa dịch vụ thành công!']);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
                    }
                }

                // Cập nhật trạng thái dịch vụ phát sinh
                if ($request->action === 'updateServicePlusStatus') {
                    $request->validate([
                        'service_plus_status' => 'required|in:not_yet_paid,paid',
                    ]);

                    try {
                        DB::beginTransaction();
                        $newStatus = $request->input('service_plus_status');

                        if ($booking->service_plus_status === 'paid') {
                            return response()->json(['success' => false, 'message' => 'Không thể thay đổi trạng thái đã thanh toán!'], 400);
                        }

                        $booking->update(['service_plus_status' => $newStatus]);
                        DB::commit();
                        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công!']);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
                    }
                }
            }

            return response()->json(['success' => false, 'message' => 'Hành động không hợp lệ!'], 400);
        } catch (\Exception $e) {
            Log::error('Error in updateServicePlus method: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
        }
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
        if ($currentStatus === 'pending_confirmation' && $newStatus === 'confirmed') {
            // Nếu trạng thái hiện tại là "Chưa xác nhận", cho phép đổi sang đã sác nhận
            $booking->update(['status' => $newStatus]);
            return redirect()->route('admin.bookings.index')->with('success', 'Cập nhật trạng thái đặt phòng thành công.');
        } elseif ($currentStatus === 'confirmed' && in_array($newStatus, ['paid', 'cancelled'])) {
            // Nếu trạng thái hiện tại là "Đã xác nhận", chỉ cho phép đổi sang "Đã thanh toán" hoặc "Đã hủy"
            $booking->update(['status' => $newStatus]);
            return redirect()->route('admin.bookings.index')->with('success', 'Cập nhật trạng thái đặt phòng thành công.');
        } elseif ($currentStatus === 'paid' && in_array($newStatus, ['check_in', 'refunded'])) {
            // Nếu trạng thái hiện tại là "Đã thanh toán", chỉ cho phép đổi sang "Đã check in", "Đã hủy", hoặc "Đã hoàn tiền"
            $booking->update([
                'status' => $newStatus,
                'actual_check_in' => now(), // Cập nhật thời gian check-in thực tế
            ]);
            return redirect()->route('admin.bookings.index')->with('success', 'Cập nhật trạng thái đặt phòng thành công.');
        } elseif ($currentStatus === 'check_in' && $newStatus === 'check_out') {
            // Nếu trạng thái hiện tại là "Đã check in", chỉ cho phép đổi sang "Đã checkout" và cập nhật actual_check_out
            $booking->update([
                'status' => $newStatus,
                'actual_check_out' => now(), // Cập nhật thời gian check-out thực tế
            ]);
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
