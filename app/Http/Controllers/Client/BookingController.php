<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Guest;
use App\Models\Booking;
use App\Models\RoomType;
use App\Models\Promotion;
use App\Models\Payment;
use App\Models\ServicePlus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function index()
    {
        $title = 'Danh sách đặt phòng của bạn';

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem danh sách đặt phòng.');
        }

        $bookings = Booking::with(['rooms', 'rooms.roomType', 'rooms.roomType.roomTypeImages'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('clients.bookings.index', compact('bookings', 'title'));
    }

    public function create(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đặt phòng.');
        }

        // Đặt múi giờ
        Carbon::setLocale('vi');
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        // Lấy dữ liệu từ request
        $roomTypeId = $request->input('room_type_id');
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');
        $totalGuests = (int) $request->input('total_guests', 2);
        $childrenCount = (int) $request->input('children_count', 0);
        $roomQuantity = (int) $request->input('room_quantity', 1);
        $services = $request->input('services', []);

        // Lấy các giá trị giá từ request
        $basePrice = (float) $request->input('base_price');
        $discountedPrice = (float) $request->input('discounted_price');
        $discountAmount = (float) $request->input('discount_amount');
        $serviceTotal = (float) $request->input('service_total');

        // Tính lại thuế và tổng giá
        $subTotal = $discountedPrice + $serviceTotal; // Tổng phụ = Giá sau giảm + Dịch vụ
        $taxFee = $subTotal * 0.08; // Thuế 8% của tổng phụ
        $totalPrice = $subTotal + $taxFee; // Tổng giá = Tổng phụ + Thuế

        // Xác thực dữ liệu
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'total_guests' => 'required|integer|min:1',
            'children_count' => 'required|integer|min:0',
            'room_quantity' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'discounted_price' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'service_total' => 'required|numeric|min:0',
        ]);

        // Xử lý ngày giờ
        $checkIn = Carbon::parse($checkIn);
        $checkOut = Carbon::parse($checkOut);
        $now = Carbon::now();

        // Nếu ngày check-in là quá khứ hoặc hôm nay sau 22:00, điều chỉnh sang ngày hôm sau
        if ($checkIn->lt($now->startOfDay()) || ($checkIn->isToday() && $now->hour >= 22)) {
            $checkIn = $now->copy()->addDay()->startOfDay();
            $checkOut = $checkIn->copy()->addDay();
            $request->session()->flash('warning', 'Đặt phòng vào thời điểm này sẽ được check-in từ ngày mai (' . $checkIn->format('d/m/Y') . ').');
        }

        // Kiểm tra check-out phải sau check-in
        if ($checkIn->gte($checkOut)) {
            $checkOut = $checkIn->copy()->addDay();
            $request->session()->flash('warning', 'Ngày trả phòng đã được điều chỉnh để sau ngày nhận phòng.');
        }

        // Tính số ngày lưu trú
        $days = $checkOut->diffInDays($checkIn);

        // Lấy thông tin loại phòng
        $selectedRoomType = RoomType::with([
            'amenities' => function ($query) {
                $query->where('is_active', true);
            },
            'services' => function ($query) {
                $query->where('is_active', true);
            },
            'roomTypeImages',
            'rooms'
        ])->findOrFail($roomTypeId);

        // Lấy danh sách phòng còn trống
        $allRooms = $selectedRoomType->rooms;
        $bookedRoomIds = Booking::whereHas('rooms', function ($query) use ($selectedRoomType) {
            $query->where('room_type_id', $selectedRoomType->id);
        })
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<=', $checkIn)
                            ->where('check_out', '>=', $checkOut);
                    });
            })
            ->where(function ($q) use ($checkIn) {
                $q->whereNull('actual_check_out')
                    ->orWhere('actual_check_out', '>=', $checkIn);
            })
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->with('rooms')
            ->get()
            ->flatMap(function ($booking) {
                return $booking->rooms->pluck('id');
            })
            ->unique()
            ->toArray();

        $availableRooms = $allRooms->whereNotIn('id', $bookedRoomIds);
        $availableRoomCount = $availableRooms->count();

        // Kiểm tra số lượng phòng yêu cầu
        if ($roomQuantity > $availableRoomCount) {
            return redirect()->route('home')->with('error', "Số lượng phòng yêu cầu ($roomQuantity) vượt quá số phòng còn trống ($availableRoomCount).");
        }

        $selectedRooms = $availableRooms->take($roomQuantity);

        return view('clients.bookings.create', compact(
            'selectedRoomType',
            'checkIn',
            'checkOut',
            'totalGuests',
            'childrenCount',
            'roomQuantity',
            'services',
            'selectedRooms',
            'availableRoomCount',
            'days',
            'basePrice',
            'discountedPrice',
            'discountAmount',
            'serviceTotal',
            'taxFee',
            'totalPrice'
        ));
    }

    public function confirm(Request $request)
    {
        if ($request->isMethod('post')) {
            // Chuyển đổi birth_date nếu có
            if ($request->filled('birth_date')) {
                try {
                    $birthDate = $request->input('birth_date');
                    if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $birthDate)) {
                        $birthDate = \Carbon\Carbon::createFromFormat('d/m/Y', $birthDate)->format('Y-m-d');
                        $request->merge(['birth_date' => $birthDate]);
                    }
                } catch (\Exception $e) {
                    // Nếu không parse được, giữ nguyên giá trị và để validation xử lý
                }
            }

            $validated = $request->validate([
                'check_in' => 'required|date|after_or_equal:today',
                'check_out' => 'required|date|after:check_in',
                'total_guests' => 'required|integer|min:1',
                'children_count' => 'required|integer|min:0',
                'room_type_id' => 'required|exists:room_types,id',
                'room_quantity' => 'required|integer|min:1',
                'special_request' => 'nullable|string',
                'guests' => 'required|array|min:1',
                'guests.*.name' => 'required|string|max:255',
                'guests.*.id_number' => 'nullable|string|regex:/^[0-9]{9,12}$/',
                'guests.*.id_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'guests.*.birth_date' => 'nullable|date|before:today',
                'guests.*.gender' => 'nullable|in:male,female,other',
                'guests.*.phone' => 'nullable|string|regex:/^[0-9]{10,15}$/',
                'guests.*.email' => 'nullable|email',
                'guests.*.relationship' => 'nullable|string|max:50',
                'services' => 'nullable|array',
                'service_quantity_*' => 'nullable|integer|min:1',
                'discount_amount' => 'nullable|numeric|min:0',
                'total_price' => 'required|numeric|min:0',
                'base_price' => 'required|numeric|min:0',
                'service_total' => 'required|numeric|min:0',
            ]);

            // Lưu file id_photo tạm thời và thêm đường dẫn vào dữ liệu guests
            $guests = $request->input('guests');
            foreach ($guests as $index => &$guest) {
                if ($request->hasFile("guests.$index.id_photo")) {
                    $path = $request->file("guests.$index.id_photo")->store('temp_id_photos', 'public');
                    $guest['id_photo_path'] = $path;
                }
            }
            $request->merge(['guests' => $guests]);

            $roomType = RoomType::with('services')->findOrFail($request->room_type_id);
            $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);
            $days = $checkOut->diffInDays($checkIn);

            // Lấy các giá trị từ request
            $basePrice = (float) $request->base_price;
            $serviceTotal = (float) $request->service_total;
            $discountAmount = (float) $request->discount_amount;
            $totalGuests = (int) $request->total_guests;
            $childrenCount = (int) $request->children_count;
            $roomQuantity = (int) $request->room_quantity;

            // Tính toán lại
            $subTotal = $basePrice + $serviceTotal - $discountAmount;
            $taxFee = $subTotal * 0.08;
            $totalPrice = $subTotal + $taxFee;

            // Lấy danh sách dịch vụ và số lượng
            $selectedServices = [];
            $serviceQuantities = [];
            if (!empty($request->services)) {
                $selectedServices = $roomType->services->whereIn('id', $request->services)->all();
                foreach ($selectedServices as $service) {
                    $serviceQuantities[$service->id] = $request->input("service_quantity_{$service->id}", 1);
                }
            }

            return view('clients.bookings.confirm', compact(
                'roomType',
                'checkIn',
                'checkOut',
                'days',
                'basePrice',
                'serviceTotal',
                'subTotal',
                'taxFee',
                'totalPrice',
                'selectedServices',
                'discountAmount',
                'totalGuests',
                'childrenCount',
                'roomQuantity',
                'serviceQuantities'
            ));
        }

        return redirect()->route('bookings.create')->with('error', 'Không thể truy cập trực tiếp trang này.');
    }

    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $validated = $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'total_guests' => 'required|integer|min:1',
            'children_count' => 'required|integer|min:0',
            'room_type_id' => 'required|exists:room_types,id',
            'room_quantity' => 'required|integer|min:1',
            'special_request' => 'nullable|string',
            'guests' => 'required|array|min:1',
            'guests.*.name' => 'required|string|max:255',
            'guests.*.id_number' => 'nullable|string|regex:/^[0-9]{9,12}$/',
            'guests.*.id_photo_path' => 'nullable|string',
            'guests.*.birth_date' => 'nullable|date|before:today',
            'guests.*.gender' => 'nullable|in:male,female,other',
            'guests.*.phone' => 'nullable|string|regex:/^[0-9]{10,15}$/',
            'guests.*.email' => 'nullable|email',
            'guests.*.relationship' => 'nullable|string|max:50',
            'services' => 'nullable|array',
            'service_quantity_*' => 'nullable|integer|min:1',
            'discount_amount' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,online',
            'online_payment_method' => 'required_if:payment_method,online|in:momo,vnpay',
            'base_price' => 'required|numeric|min:0',
            'service_total' => 'required|numeric|min:0',
            'tax_fee' => 'required|numeric|min:0',
            'sub_total' => 'required|numeric|min:0',
        ]);

        // Kiểm tra người dùng đã đăng nhập
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đặt phòng.');
        }

        // Kiểm tra phương thức thanh toán
        $paymentMethod = $request->input('payment_method');
        $onlinePaymentMethod = $request->input('online_payment_method', null);
        if ($paymentMethod === 'online' && !$onlinePaymentMethod) {
            return redirect()->back()->with('error', 'Vui lòng chọn một cổng thanh toán (MoMo hoặc VNPay).');
        }

        // Xử lý ngày giờ
        $checkIn = Carbon::parse($validated['check_in'])->setTime(14, 0, 0);
        $checkOut = Carbon::parse($validated['check_out'])->setTime(12, 0, 0);
        $days = $checkOut->diffInDays($checkIn);

        // Lấy thông tin loại phòng
        $roomType = RoomType::findOrFail($validated['room_type_id']);

        // Lấy các giá trị từ request
        $basePrice = (float) $request->input('base_price');
        $serviceTotal = (float) $request->input('service_total');
        $discountAmount = (float) $request->input('discount_amount', 0);
        $taxFee = (float) $request->input('tax_fee');
        $subTotal = (float) $request->input('sub_total');

        // Tính lại tổng giá để đảm bảo chính xác
        $calculatedSubTotal = $basePrice + $serviceTotal - $discountAmount;
        $calculatedTaxFee = $calculatedSubTotal * 0.08;
        $totalPrice = $calculatedSubTotal + $calculatedTaxFee;

        // So sánh với total_price từ request
        $requestTotalPrice = (float) $request->input('total_price');
        if (abs($totalPrice - $requestTotalPrice) > 0.01) {
            Log::warning('Total price mismatch', [
                'calculated' => $totalPrice,
                'request' => $requestTotalPrice,
            ]);
            $totalPrice = $requestTotalPrice; // Sử dụng giá trị từ request nếu có sự khác biệt
        }

        // Tạo booking mới
        $booking = Booking::create([
            'booking_code' => 'BOOK' . time(),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'total_price' => $totalPrice,
            'base_price' => $basePrice,
            'service_total' => $serviceTotal,
            'tax_fee' => $taxFee,
            'sub_total' => $subTotal,
            'discount_amount' => $discountAmount,
            'total_guests' => $validated['total_guests'],
            'children_count' => $validated['children_count'],
            'user_id' => $user->id,
            'room_type_id' => $validated['room_type_id'],
            'room_quantity' => $validated['room_quantity'],
            'special_request' => $request->input('special_request'),
            'service_plus_status' => !empty($validated['services']) ? 'not_yet_paid' : 'none',
            'payment_method' => $paymentMethod == 'cash' ? 'cash' : $onlinePaymentMethod,
            'status' => 'pending_confirmation',
        ]);

        // Kiểm tra và gắn phòng
        $allRooms = $roomType->rooms;
        $bookedRoomIds = Booking::whereHas('rooms', function ($query) use ($roomType) {
            $query->where('room_type_id', $roomType->id);
        })
            ->where('id', '!=', $booking->id)
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<=', $checkIn)
                            ->where('check_out', '>=', $checkOut);
                    });
            })
            ->with('rooms')
            ->get()
            ->flatMap(function ($booking) {
                return $booking->rooms->pluck('id');
            })
            ->unique()
            ->toArray();

        $availableRooms = $allRooms->whereNotIn('id', $bookedRoomIds);
        $roomQuantity = $validated['room_quantity'];

        if ($roomQuantity > $availableRooms->count()) {
            $booking->delete();
            return redirect()->route('home')->with('error', 'Không đủ phòng trống để đặt.');
        }

        $selectedRooms = $availableRooms->take($roomQuantity);
        $booking->rooms()->attach($selectedRooms->pluck('id'));

        // Gắn dịch vụ bổ sung
        if (!empty($validated['services'])) {
            $serviceData = [];
            foreach ($validated['services'] as $serviceId) {
                $quantity = $request->input("service_quantity_{$serviceId}", 1);
                $serviceData[$serviceId] = ['quantity' => $quantity];
            }
            $booking->servicePlus()->attach($serviceData);
        }

        // Gắn khách
        $guestIds = [];
        foreach ($validated['guests'] as $index => $guestData) {
            $idPhotoPath = null;
            if (isset($guestData['id_photo_path']) && Storage::disk('public')->exists($guestData['id_photo_path'])) {
                $tempPath = $guestData['id_photo_path'];
                $newPath = 'id_photos/' . basename($tempPath);
                Storage::disk('public')->move($tempPath, $newPath);
                $idPhotoPath = $newPath;
            }

            $guest = Guest::create([
                'name' => $guestData['name'],
                'id_number' => $guestData['id_number'] ?? null,
                'id_photo' => $idPhotoPath,
                'birth_date' => $guestData['birth_date'] ?? null,
                'gender' => $guestData['gender'] ?? null,
                'phone' => $guestData['phone'] ?? null,
                'email' => $guestData['email'] ?? null,
                'relationship' => $guestData['relationship'] ?? null,
            ]);
            $guestIds[] = $guest->id;
        }
        $booking->guests()->attach($guestIds);

        // Tạo thanh toán
        $paymentData = [
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'amount' => $totalPrice,
            'status' => 'pending',
            'transaction_id' => null,
        ];

        if ($paymentMethod == 'cash') {
            $paymentData['method'] = 'cash';
            $payment = Payment::create($paymentData);
            $message = 'Đặt phòng đã được hoàn tất! Vui lòng thanh toán bằng tiền mặt khi nhận phòng.';
            return redirect()->route('bookings.show', $booking->id)->with('success', $message);
        } else {
            $paymentData['method'] = $onlinePaymentMethod;
            $payment = Payment::create($paymentData);

            if ($onlinePaymentMethod == 'momo') {
                $partnerCode = env('MOMO_PARTNER_CODE');
                $accessKey = env('MOMO_ACCESS_KEY');
                $secretKey = env('MOMO_SECRET_KEY');
                $endpoint = env('MOMO_ENDPOINT');
                $redirectUrl = env('MOMO_REDIRECT_URL');
                $ipnUrl = env('MOMO_IPN_URL');

                $orderId = $booking->booking_code . '-' . time();
                $requestId = time() . '';
                $orderInfo = 'Thanh toán đặt phòng ' . $booking->booking_code;
                $amount = (int) $totalPrice;
                $extraData = base64_encode(json_encode(['booking_id' => $booking->id]));

                $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=captureWallet";
                $signature = hash_hmac("sha256", $rawHash, $secretKey);

                $data = [
                    'partnerCode' => $partnerCode,
                    'partnerName' => "Hotel Booking",
                    'storeId' => "HotelBookingStore",
                    'requestId' => $requestId,
                    'amount' => $amount,
                    'orderId' => $orderId,
                    'orderInfo' => $orderInfo,
                    'redirectUrl' => $redirectUrl,
                    'ipnUrl' => $ipnUrl,
                    'lang' => 'vi',
                    'extraData' => $extraData,
                    'requestType' => 'captureWallet',
                    'signature' => $signature,
                ];

                try {
                    $response = Http::post($endpoint, $data);
                    $result = $response->json();

                    if (isset($result['payUrl']) && isset($result['qrCodeUrl'])) {
                        $payment->update(['transaction_id' => $orderId]);
                        return response()->json([
                            'success' => true,
                            'qrCodeUrl' => $result['qrCodeUrl'],
                            'payUrl' => $result['payUrl'],
                        ]);
                    } else {
                        $booking->delete();
                        $payment->delete();
                        return response()->json([
                            'success' => false,
                            'message' => 'Không thể tạo yêu cầu thanh toán MoMo. Vui lòng thử lại.',
                        ], 400);
                    }
                } catch (\Exception $e) {
                    $booking->delete();
                    $payment->delete();
                    return response()->json([
                        'success' => false,
                        'message' => 'Lỗi khi gọi API MoMo: ' . $e->getMessage(),
                    ], 500);
                }
            } else if ($onlinePaymentMethod == 'vnpay') {

                $paymentData = session()->get('payment_data', []);

                $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
                $vnp_Returnurl = route('bookings.return.vnpay', $booking->id);
                $vnp_TmnCode = "6Q5Z9DG8"; //Mã website tại VNPAY
                $vnp_HashSecret = "NSEYDYAIT1XETEVUA24DF40DOCMC6NYE"; //Chuỗi bí mật

                $vnp_TxnRef = $booking->booking_code . '-' . time(); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này

                $vnp_OrderInfo = 'Thanh toán đặt phòng ' . $booking->booking_code;
                $vnp_OrderType = 'billpayment';
                $vnp_Amount = (int) $totalPrice * 100;
                $vnp_Locale = 'vn';
                $vnp_BankCode = '';
                $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
                //Add Params of 2.0.1 Version
                // $vnp_ExpireDate = $_POST['txtexpire'];

                $inputData = array(
                    "vnp_Version" => "2.1.0",
                    "vnp_TmnCode" => $vnp_TmnCode,
                    "vnp_Amount" => $vnp_Amount,
                    "vnp_Command" => "pay",
                    "vnp_CreateDate" => date('YmdHis'),
                    "vnp_CurrCode" => "VND",
                    "vnp_IpAddr" => $vnp_IpAddr,
                    "vnp_Locale" => $vnp_Locale,
                    "vnp_OrderInfo" => $vnp_OrderInfo,
                    "vnp_OrderType" => $vnp_OrderType,
                    "vnp_ReturnUrl" => $vnp_Returnurl,
                    "vnp_TxnRef" => $vnp_TxnRef
                    // "vnp_ExpireDate" => $vnp_ExpireDate,
                );

                if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                    $inputData['vnp_BankCode'] = $vnp_BankCode;
                }

                //var_dump($inputData);
                ksort($inputData);
                $query = "";
                $i = 0;
                $hashdata = "";
                foreach ($inputData as $key => $value) {
                    if ($i == 1) {
                        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                    } else {
                        $hashdata .= urlencode($key) . "=" . urlencode($value);
                        $i = 1;
                    }
                    $query .= urlencode($key) . "=" . urlencode($value) . '&';
                }

                $vnp_Url = $vnp_Url . "?" . $query;
                if (isset($vnp_HashSecret)) {
                    $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //
                    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
                }
                $returnData = array(
                    'code' => '00',
                    'message' => 'success',
                    'data' => $vnp_Url
                );
                if (isset($request)) {
                    // Chuyển hướng tới trang thanh toán của VNPAY
                    return redirect($vnp_Url);
                } else {
                    echo json_encode($returnData);
                }
            } else {
                $methodName = $onlinePaymentMethod == 'momo' ? 'MoMo' : 'VNPay';
                $message = "Đặt phòng đã được hoàn tất! Bạn đã chọn thanh toán qua $methodName, vui lòng hoàn tất thanh toán sau.";
                return redirect()->route('bookings.show', $booking->id)->with('success', $message);
            }
        }
    }


    public function paymentCallback(Request $request)
    {
        $data = $request->all();
        $secretKey = env('MOMO_SECRET_KEY');

        $rawHash = "accessKey=" . env('MOMO_ACCESS_KEY') . "&amount=" . $data['amount'] . "&extraData=" . $data['extraData'] . "&message=" . $data['message'] . "&orderId=" . $data['orderId'] . "&orderInfo=" . $data['orderInfo'] . "&orderType=" . $data['orderType'] . "&partnerCode=" . $data['partnerCode'] . "&payType=" . $data['payType'] . "&requestId=" . $data['requestId'] . "&responseTime=" . $data['responseTime'] . "&resultCode=" . $data['resultCode'] . "&transId=" . $data['transId'];
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        if ($signature !== $data['signature']) {
            Log::error('MoMo Callback - Invalid signature', ['data' => $data]);
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
        }

        $extraData = json_decode(base64_decode($data['extraData']), true);
        $bookingId = $extraData['booking_id'];

        $booking = Booking::findOrFail($bookingId);
        $payment = Payment::where('booking_id', $bookingId)->first();

        if ($data['resultCode'] == 0) {
            $payment->update([
                'status' => 'completed',
                'transaction_id' => $data['transId'],
            ]);
            $booking->update(['status' => 'confirmed']);
            $message = 'Thanh toán thành công! Đặt phòng của bạn đã được xác nhận.';
        } else {
            $payment->update(['status' => 'failed']);
            $booking->update(['status' => 'cancelled']);
            $message = 'Thanh toán thất bại. Đặt phòng của bạn đã bị hủy.';
        }

        return redirect()->route('bookings.show', $booking->id)->with('success', $message);
    }

    public function show(string $id)
    {
        $booking = Booking::with([
            'user',
            'rooms.roomType' => function ($query) {
                $query->with(['amenities', 'rulesAndRegulations', 'services', 'roomTypeImages']);
            },
            'rooms' => function ($query) {
                $query->withTrashed();
            },
            'servicePlus',
            'payments',
            'guests',
        ])->findOrFail($id);

        $title = 'Chi tiết đơn đặt phòng';
        return view('clients.bookings.show', compact('title', 'booking'));
    }

    public function edit(string $id)
    {
        $booking = Booking::with(['rooms', 'rooms.roomType', 'servicePlus'])->findOrFail($id);
        return view('clients.bookings.edit', compact('booking'));
    }

    public function update(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);
        $currentStatus = $booking->status;
        $newStatus = $request->input('status');

        if ($currentStatus === 'pending_confirmation' && $newStatus === 'cancelled') {
            $booking->update(['status' => $newStatus]);
            return redirect()->route('bookings.index')->with('success', 'Hủy đặt phòng thành công!');
        }

        return redirect()->back()->with('error', 'Không thể thay đổi trạng thái từ ' . $currentStatus . ' sang ' . $newStatus . '.');
    }

    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        if ($booking->user_id === Auth::id()) {
            $booking->delete();
            return redirect()->route('client.bookings.index')->with('success', 'Xóa đơn đặt thành công!');
        }
        return redirect()->back()->with('error', 'Bạn không có quyền xóa đơn đặt này!');
    }

    public function checkPromotion(Request $request)
    {
        $promotionCode = $request->input('code');
        $totalPrice = (float) $request->input('total_price');
        $basePrice = (float) $request->input('base_price');
        $serviceTotal = (float) $request->input('service_total');

        // Tính tổng phụ trước thuế
        $subTotal = $basePrice + $serviceTotal;

        $promotion = Promotion::where('code', $promotionCode)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('quantity', '>', 0)
            ->where('status', 'active')
            ->first();

        if ($promotion) {
            // Áp dụng mã giảm giá trên tổng phụ
            $discountAmount = $promotion->discount_type === 'percentage'
                ? $subTotal * ($promotion->discount_value / 100)
                : $promotion->discount_value;

            // Đảm bảo số tiền giảm không vượt quá tổng phụ
            $discountAmount = min($discountAmount, $subTotal);

            // Tính lại tổng phụ sau giảm giá
            $newSubTotal = $subTotal - $discountAmount;
            $taxFee = $newSubTotal * 0.08; // Thuế 8% của tổng phụ mới
            $newTotalPrice = $newSubTotal + $taxFee; // Tổng giá mới

            return response()->json([
                'success' => true,
                'discount_amount' => $discountAmount,
                'new_total_price' => $newTotalPrice,
                'tax_fee' => $taxFee,
                'message' => 'Mã giảm giá hợp lệ!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'
        ]);
    }





    public function returnVnpay(Request $request)
    {
        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $booking_id = $request->id;
        if ($vnp_ResponseCode == '00') {
            try {
                DB::transaction(function () use ($booking_id, $request) {
                    // Cập nhật trạng thái đơn đặt vé
                    Booking::where('id', $booking_id)->update(['status' => 'paid']);

                    // Cập nhật trạng thái thanh toán nếu có
                    $payment = Payment::where('booking_id', $booking_id)->first();
                    if ($payment) {
                        $payment->update([
                            'transaction_id' => $request->input('vnp_TransactionNo'), // Lấy dữ liệu an toàn hơn
                            'status' => 'completed'
                        ]);
                    }
                });

                return redirect()->route('bookings.show', $booking_id)
                    ->with('success', 'Thanh toán thành công!');
            } catch (\Throwable $th) {
                return redirect()->route('bookings.show', $booking_id)
                    ->with('error', 'Đã có lỗi xảy ra trong quá trình cập nhật thanh toán.');
            }
        } else {
            return redirect()->route('bookings.show', $booking_id)
                ->with('error', 'Thanh toán không thành công!');
        }
    }
}
