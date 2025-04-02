<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Guest;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\ServicePlus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorebookingRequest;
use App\Http\Requests\StoreCheckInRequest;
use App\Http\Requests\UpdatebookingRequest;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $title = 'Đơn đặt phòng mới nhất';

    // Khởi tạo query
    $query = Booking::with('user', 'rooms')->latest();

    // Lọc theo khoảng thời gian
    if ($request->has('start_date') && $request->has('end_date')) {
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
        $query->whereBetween('created_at', [$startDate, $endDate]);
    } elseif ($request->has('filter')) {
        switch ($request->input('filter')) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'this_week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                      ->whereYear('created_at', Carbon::now()->subMonth()->year);
                break;
        }
    }

    // Lọc theo trạng thái (nếu có)
    if ($request->has('status') && $request->input('status') !== '') {
        $query->where('status', $request->input('status'));
    }

    // Phân trang
    $bookings = $query->paginate(10);

    // Truyền thêm dữ liệu lọc để hiển thị lại trên giao diện
    $filterData = [
        'start_date' => $request->input('start_date'),
        'end_date' => $request->input('end_date'),
        'filter' => $request->input('filter'),
        'status' => $request->input('status'),
    ];

    return view('admins.bookings.index', compact('bookings', 'title', 'filterData'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.bookings.create');
    }


    public function storeCheckIn(StoreCheckInRequest $request)
    {
        try {
            Log::info('Received check-in request', ['request_data' => $request->all()]);

            $booking = Booking::findOrFail($request->booking_id);

            if ($booking->status !== 'paid') {
                Log::info('Booking status not paid', ['booking_id' => $request->booking_id, 'status' => $booking->status]);
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể check-in: Đặt phòng chưa ở trạng thái "Đã thanh toán". Vui lòng kiểm tra lại trạng thái thanh toán.'
                ], 400);
            }

            DB::beginTransaction();

            foreach ($request->guests as $index => $guestData) {
                if ($request->hasFile("guests.$index.id_photo")) {
                    $file = $request->file("guests.$index.id_photo");
                    $fileName = time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('id_photos', $fileName, 'public');
                    $guestData['id_photo'] = 'id_photos/' . $fileName;
                }

                $guest = Guest::create($guestData);
                $booking->guests()->attach($guest->id);
            }

            DB::commit();

            Log::info('Check-in successful', ['booking_id' => $request->booking_id]);
            return response()->json([
                'success' => true,
                'message' => 'Check-in thành công và thông tin người ở đã được lưu.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in storeCheckIn: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: Không thể lưu thông tin người ở. Vui lòng thử lại sau.'
            ], 500);
        }
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
        $newStatus = $request->input('status'); // Giả sử trạng thái mới được gửi từ form

        if ($currentStatus === 'confirmed' && in_array($newStatus, ['paid', 'cancelled'])) {
            // Nếu trạng thái hiện tại là "Đã xác nhận", chỉ cho phép đổi sang "Đã thanh toán" hoặc "Đã hủy"
            if ($newStatus === 'paid') {
                // Tìm bản ghi thanh toán liên quan
                $payment = Payment::where('booking_id', $booking->id)->first();
                if (!$payment) {
                    return redirect()->back()->with('error', 'Không tìm thấy bản ghi thanh toán cho đặt phòng này.');
                }

                // Kiểm tra trạng thái thanh toán
                if ($payment->status !== 'pending') {
                    return redirect()->back()->with('error', 'Thanh toán không ở trạng thái "Chưa thanh toán", không thể cập nhật thành "Đã thanh toán".');
                }

                // Cập nhật trạng thái thanh toán
                $payment->update([
                    'status' => 'completed',
                ]);

                // Cập nhật trạng thái đặt phòng
                $booking->update(['status' => $newStatus]);
            } elseif ($newStatus === 'cancelled') {
                // Khi hủy đặt phòng, cập nhật trạng thái và thời gian check-in/check-out thực tế
                $currentTime = Carbon::now('Asia/Ho_Chi_Minh');
                $booking->update([
                    'status' => $newStatus,
                    'actual_check_in' => $currentTime,
                    'actual_check_out' => $currentTime,
                ]);
            }

            return redirect()->route('admin.bookings.index')->with('success', 'Cập nhật trạng thái đặt phòng thành công.');
        } elseif ($currentStatus === 'paid' && in_array($newStatus, ['check_in', 'refunded'])) {
            // Nếu trạng thái hiện tại là "Đã thanh toán", chỉ cho phép đổi sang "Đã check in" hoặc "Đã hoàn tiền"
            $booking->update([
                'status' => $newStatus,
                'actual_check_in' => Carbon::now('Asia/Ho_Chi_Minh'), // Cập nhật thời gian check-in thực tế
            ]);
            return redirect()->route('admin.bookings.index')->with('success', 'Cập nhật trạng thái đặt phòng thành công.');
        } elseif ($currentStatus === 'check_in' && $newStatus === 'check_out') {
            // Nếu trạng thái hiện tại là "Đã check in", chỉ cho phép đổi sang "Đã checkout" và cập nhật actual_check_out
            $booking->update([
                'status' => $newStatus,
                'actual_check_out' => Carbon::now('Asia/Ho_Chi_Minh'), // Cập nhật thời gian check-out thực tế
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
