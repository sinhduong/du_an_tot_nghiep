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
use App\Mail\PaymentSuccess;
use Illuminate\Support\Facades\Mail;

class BookingController extends BaseAdminController
{
    public function __construct()
    {
        // Gán middleware để kiểm soát quyền truy cập cho các phương thức
        $this->middleware('permission:bookings_list')->only(['index']);
        $this->middleware('permission:bookings_create')->only(['create', 'store']);
        $this->middleware('permission:bookings_detail')->only(['show']);
        $this->middleware('permission:bookings_update')->only(['edit', 'update']);
        $this->middleware('permission:bookings_delete')->only(['destroy']);
        $this->middleware('permission:bookings_checkin')->only(['storeCheckIn']);
        $this->middleware('permission:bookings_service_plus')->only(['updateServicePlus']);


    }
    public function index(Request $request)
    {
        $title = 'Đơn đặt phòng mới nhất';

        // Khởi tạo query
        $query = Booking::with('user', 'rooms', 'refund', 'refund.refundPolicy')->latest();

        // Lọc theo khoảng thời gian
        if ($request->has('start_date') && $request->has('end_date') && $request->input('start_date') && $request->input('end_date')) {
            $startDate = $request->input('start_date') . ' 00:00:00';
            $endDate = $request->input('end_date') . ' 23:59:59';
            if ($startDate) {
                $query->where('check_in', '>=', $startDate);
            }
            if ($endDate) {
                $query->where('check_out', '<=', $endDate);
            }
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->input('status') !== null) {
            $query->where('status', $request->input('status'));
        }

        // Phân trang
        $bookings = $query->paginate(10);

        // Truyền dữ liệu lọc để hiển thị lại trên giao diện
        $filterData = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
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

            // Kiểm tra thời gian check-in phải sau 14h ngày check-in
            $checkInDate = Carbon::parse($booking->check_in);
            $currentTime = Carbon::now();
            $checkInTime = $checkInDate->copy()->setTime(14, 0, 0);

            if ($currentTime->lt($checkInTime)) {
                Log::info('Check-in time not allowed', [
                    'booking_id' => $request->booking_id,
                    'current_time' => $currentTime,
                    'required_check_in_time' => $checkInTime
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể check-in: Chỉ được phép check-in sau 14:00 ngày ' . $checkInDate->format('d/m/Y')
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

        if (request()->ajax()) {
            return response()->json(['booking' => $booking]);
        }

        $title = 'Chi tiết đơn đặt phòng';
        $availableServicePlus = ServicePlus::where('is_active', 1)->get();
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

                        // Tính toán tổng phí dịch vụ phát sinh
                        $servicePrice = $servicePlus->price * $quantity;
                        $currentServiceTotal = $booking->service_plus_total ?? 0;
                        $newServiceTotal = $currentServiceTotal + $servicePrice;

                        // Cập nhật service_plus_total và total_price
                        $booking->update([
                            'service_plus_total' => $newServiceTotal,
                            'total_price' => $booking->total_price + $servicePrice
                        ]);

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
                        // Kiểm tra trạng thái thanh toán
                        if ($booking->service_plus_status === 'paid') {
                            return response()->json([
                                'success' => false,
                                'message' => 'Không thể cập nhật số lượng vì dịch vụ phát sinh đã thanh toán!'
                            ], 400);
                        }

                        DB::beginTransaction();
                        $servicePlusId = $request->input('service_plus_id');
                        $newQuantity = $request->input('quantity');

                        // Lấy thông tin dịch vụ và số lượng hiện tại
                        $servicePlus = ServicePlus::find($servicePlusId);
                        $currentPivot = $booking->servicePlus()->where('service_plus_id', $servicePlusId)->first();
                        $oldQuantity = $currentPivot->pivot->quantity;

                        // Tính toán chênh lệch giá
                        $oldPrice = $servicePlus->price * $oldQuantity;
                        $newPrice = $servicePlus->price * $newQuantity;
                        $priceDifference = $newPrice - $oldPrice;

                        // Cập nhật số lượng mới
                        $booking->servicePlus()->updateExistingPivot($servicePlusId, ['quantity' => $newQuantity]);

                        // Cập nhật tổng phí dịch vụ và tổng giá
                        $booking->update([
                            'service_plus_total' => $booking->service_plus_total + $priceDifference,
                            'total_price' => $booking->total_price + $priceDifference
                        ]);

                        DB::commit();

                        return response()->json([
                            'success' => true,
                            'message' => 'Cập nhật số lượng thành công!',
                            'data' => [
                                'id' => $servicePlus->id,
                                'quantity' => $newQuantity,
                                'price_difference' => $priceDifference
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
                        // Kiểm tra trạng thái thanh toán
                        if ($booking->service_plus_status === 'paid') {
                            return response()->json([
                                'success' => false,
                                'message' => 'Không thể xóa dịch vụ vì dịch vụ phát sinh đã thanh toán!'
                            ], 400);
                        }

                        DB::beginTransaction();
                        $servicePlusId = $request->input('service_plus_id');

                        // Lấy thông tin dịch vụ và số lượng hiện tại
                        $servicePlus = ServicePlus::find($servicePlusId);
                        $currentPivot = $booking->servicePlus()->where('service_plus_id', $servicePlusId)->first();
                        $oldQuantity = $currentPivot->pivot->quantity;

                        // Tính giá trị dịch vụ cần xóa
                        $removedPrice = $servicePlus->price * $oldQuantity;

                        // Xóa dịch vụ
                        $booking->servicePlus()->detach($servicePlusId);

                        // Cập nhật tổng phí dịch vụ và tổng giá
                        $booking->update([
                            'service_plus_total' => $booking->service_plus_total - $removedPrice,
                            'total_price' => $booking->total_price - $removedPrice
                        ]);

                        DB::commit();

                        return response()->json([
                            'success' => true,
                            'message' => 'Xóa dịch vụ thành công!',
                            'data' => [
                                'removed_price' => $removedPrice
                            ]
                        ]);
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

                        $booking->update(
                            ['service_plus_status' => $newStatus],
                            ['paid_amount' => $booking->total_price]
                        );

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
        $newStatus = $request->input('status');

        try {
            DB::beginTransaction();

            // Kiểm tra quy tắc chuyển trạng thái
            $allowedTransitions = [
                'unpaid' => ['cancelled'],
                'partial' => ['paid', 'cancelled'],
                'paid' => ['check_in', 'cancelled'],
                'check_in' => ['check_out', 'cancelled'],
                'check_out' => [],
                'cancelled' => [],
                'refunded' => []
            ];

            if (!in_array($newStatus, $allowedTransitions[$currentStatus] ?? [])) {
                throw new \Exception('Không thể chuyển từ trạng thái "' . \App\Helpers\BookingStatusHelper::getStatusLabel($currentStatus) . '" sang trạng thái "' . \App\Helpers\BookingStatusHelper::getStatusLabel($newStatus) . '"');
            }

            switch ($currentStatus) {
                case 'unpaid':
                    if ($newStatus === 'cancelled') {
                        $booking->update([
                            'status' => 'cancelled',
                            'actual_check_in' => Carbon::now('Asia/Ho_Chi_Minh'),
                            'actual_check_out' => Carbon::now('Asia/Ho_Chi_Minh'),
                        ]);
                    }
                    break;

                case 'partial':
                    if ($newStatus === 'paid') {
                        $booking->update(['status' => 'paid']);
                    } elseif ($newStatus === 'cancelled') {
                        $booking->update([
                            'status' => 'cancelled',
                            'actual_check_in' => Carbon::now('Asia/Ho_Chi_Minh'),
                            'actual_check_out' => Carbon::now('Asia/Ho_Chi_Minh'),
                        ]);
                    }
                    break;

                case 'paid':
                    if ($newStatus === 'check_in') {
                        $booking->update([
                            'status' => 'check_in',
                            'actual_check_in' => Carbon::now('Asia/Ho_Chi_Minh'),
                        ]);
                    } elseif ($newStatus === 'cancelled') {
                        $booking->update([
                            'status' => 'cancelled',
                            'actual_check_in' => Carbon::now('Asia/Ho_Chi_Minh'),
                            'actual_check_out' => Carbon::now('Asia/Ho_Chi_Minh'),
                        ]);
                    }
                    break;

                case 'check_in':
                    if ($newStatus === 'check_out') {
                        $booking->update([
                            'status' => 'check_out',
                            'actual_check_out' => Carbon::now('Asia/Ho_Chi_Minh'),
                        ]);
                    } elseif ($newStatus === 'cancelled') {
                        $booking->update([
                            'status' => 'cancelled',
                            'actual_check_out' => Carbon::now('Asia/Ho_Chi_Minh'),
                        ]);
                    }
                    break;
            }

            DB::commit();
            return redirect()->route('admin.bookings.index')->with('success', 'Cập nhật trạng thái đặt phòng thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $bookings)
    {
        //
    }

    public function getRemainingAmount($id)
    {
        $booking = Booking::findOrFail($id);
        $remainingAmount = $booking->total_price - $booking->paid_amount;

        return response()->json([
            'remaining_amount' => $remainingAmount
        ]);
    }

    public function storePaid(Request $request)
    {
        $booking = Booking::findOrFail($request->id_booking);
        $remainingAmount = $booking->total_price - $booking->paid_amount;
        $paymentData = [
            'user_id' => $booking->user_id,
            'booking_id' => $request->id_booking,
            'amount' => $remainingAmount,
            'status' => 'pending',
            'transaction_id' => null,
            'is_partial' => false,
        ];
        if ($request->payment_method === 'cash') {
            $paymentData['method'] = 'cash';
            $paymentData['transaction_id'] = 'BOOK' . time();
            $payment = Payment::create($paymentData);
            $booking->update([
                'status' => 'paid',
                'paid_amount' => $booking->total_price,
            ]);
            $payment->update([
                'status' => 'completed',
            ]);
            $message = 'Thanh toán đã hoàn tất! Thông tin chi tiết đã được gửi qua email.';
            // Gửi email xác nhận
            Mail::to($booking->user->email)->send(new PaymentSuccess($booking));
            return redirect()->back()->with('success', $message);
        } elseif ($request->payment_method === 'vnpay') {
            $paymentData['method'] = 'vnpay';
            $payment = Payment::create($paymentData);
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = route('admin.bookings.return.vnpay', $booking->id);
            $vnp_TmnCode = "6Q5Z9DG8";
            $vnp_HashSecret = "NSEYDYAIT1XETEVUA24DF40DOCMC6NYE";

            $vnp_TxnRef = $booking->booking_code . '-' . time();
            $vnp_OrderInfo = 'Thanh toán đặt phòng ' . $booking->booking_code;
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = (int) $remainingAmount * 100;
            $vnp_Locale = 'vn';
            $vnp_BankCode = '';
            $vnp_IpAddr = $request->ip();
            $vnp_CreateDate = date('YmdHis');
            $vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes'));

            $inputData = [
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => $vnp_CreateDate,
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
                "vnp_ExpireDate" => $vnp_ExpireDate,
            ];

            if (!empty($vnp_BankCode)) {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }

            ksort($inputData);

            $hashdata = "";
            $first = true;
            foreach ($inputData as $key => $value) {
                if ($first) {
                    $hashdata .= $key . "=" . urlencode($value);
                    $first = false;
                } else {
                    $hashdata .= "&" . $key . "=" . urlencode($value);
                }
            }

            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= "?" . $hashdata . "&vnp_SecureHash=" . $vnpSecureHash;

            $payment->update(['transaction_id' => $vnp_TxnRef]);

            return redirect($vnp_Url);
        }
    }

    public function returnVnpay(Request $request, $id)
    {
        $vnp_HashSecret = "NSEYDYAIT1XETEVUA24DF40DOCMC6NYE"; // Đảm bảo đúng HashSecret từ VNPay

        // Lấy tất cả tham số từ VNPay trả về
        $vnp_SecureHash = $request->input('vnp_SecureHash');
        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $vnp_TransactionNo = $request->input('vnp_TransactionNo');
        $vnp_Amount = $request->input('vnp_Amount') / 100; // Chuyển đổi từ VND sang số thực

        // Loại bỏ các tham số không cần thiết để tạo chữ ký
        $inputData = $request->except(['vnp_SecureHash', 'vnp_SecureHashType']);
        ksort($inputData);

        // Tạo chuỗi dữ liệu để kiểm tra chữ ký
        $hashdata = "";
        $first = true;
        foreach ($inputData as $key => $value) {
            if ($first) {
                $hashdata .= $key . "=" . urlencode($value);
                $first = false;
            } else {
                $hashdata .= "&" . $key . "=" . urlencode($value);
            }
        }

        // Tạo chữ ký để so sánh
        $checkSum = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        // Kiểm tra chữ ký
        if ($checkSum !== $vnp_SecureHash) {
            return redirect()->route('admin.bookings.index')
                ->with('error', 'Chữ ký không hợp lệ! Thanh toán không được xác nhận.');
        }

        // Kiểm tra mã phản hồi
        if ($vnp_ResponseCode == '00') {
            try {
                DB::transaction(function () use ($id, $vnp_TransactionNo, $vnp_Amount) {
                    $booking = Booking::where('id', $id)->firstOrFail();
                    $payment = Payment::where('booking_id', $id)->first();

                    if ($payment) {
                        // Cập nhật thông tin thanh toán
                        $payment->update([
                            'transaction_id' => $vnp_TransactionNo,
                            'status' => 'completed',
                        ]);

                        // Cập nhật số tiền đã thanh toán
                        $booking->update([
                            'paid_amount' => $booking->total_price,
                            'status' => 'paid'
                        ]);

                        // Gửi email xác nhận
                        Mail::to($booking->user->email)->send(new PaymentSuccess($booking));
                    }
                });

                return redirect()->route('admin.bookings.index')
                    ->with('success', 'Thanh toán thành công! Thông tin thanh toán đã được gửi qua email.');
            } catch (\Throwable $th) {
                return redirect()->route('admin.bookings.index')
                    ->with('error', 'Đã có lỗi xảy ra trong quá trình cập nhật thanh toán: ' . $th->getMessage());
            }
        } else {
            return redirect()->route('admin.bookings.index')
                ->with('error', 'Thanh toán không thành công! Mã lỗi: ' . $vnp_ResponseCode);
        }
    }
}
