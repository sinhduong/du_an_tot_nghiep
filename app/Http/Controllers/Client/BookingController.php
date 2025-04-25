<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Guest;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\RoomType;
use App\Models\Promotion;
use App\Models\ServicePlus;
use App\Mail\BookingSuccess;
use Illuminate\Http\Request;
use App\Models\RoomTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Models\BookingRoomTypeService;
use App\Models\PaymentSetting;
use App\Models\RefundPolicy;
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

        Carbon::setLocale('vi');
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $roomTypeId = $request->input('room_type_id');
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');
        $totalGuests = (int) $request->input('total_guests', 2);
        $childrenCount = (int) $request->input('children_count', 0);
        $roomQuantity = (int) $request->input('room_quantity', 1);
        $services = $request->input('services', []); // Mảng chứa service_id => quantity

        $basePrice = (float) $request->input('base_price');
        $discountedPrice = (float) $request->input('discounted_price');
        $discountAmount = (float) $request->input('discount_amount');
        $serviceTotal = (float) $request->input('service_total');

        $subTotal = $discountedPrice + $serviceTotal;
        $taxFee = $subTotal * 0.08;
        $totalPrice = $subTotal + $taxFee;

        // dd($totalPrice, $basePrice, $discountedPrice, $serviceTotal, $taxFee);

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

        // Chuẩn hóa ngày nhận và trả phòng
        $checkIn = Carbon::parse($checkIn)->startOfDay();
        $checkOut = Carbon::parse($checkOut)->startOfDay();
        $now = Carbon::now();

        if ($checkIn->lt($now->startOfDay()) || ($checkIn->isToday() && $now->hour >= 22)) {
            $checkIn = $now->copy()->addDay()->startOfDay();
            $checkOut = $checkIn->copy()->addDay();
            $request->session()->flash('warning', 'Đặt phòng vào thời điểm này sẽ được check-in từ ngày mai (' . $checkIn->format('d/m/Y') . ').');
        }

        if ($checkIn->gte($checkOut)) {
            $checkOut = $checkIn->copy()->addDay();
            $request->session()->flash('warning', 'Ngày trả phòng đã được điều chỉnh để sau ngày nhận phòng.');
        }

        $days = $checkOut->diffInDays($checkIn);

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

        if ($roomQuantity > $availableRoomCount) {
            return redirect()->route('home')->with('error', "Số lượng phòng yêu cầu ($roomQuantity) vượt quá số phòng còn trống ($availableRoomCount).");
        }

        $selectedRooms = $availableRooms->take($roomQuantity);
        $user = Auth::user();

        $selectedServicesWithQuantity = [];
        $selectedServiceIds = array_keys(array_filter($services, fn($quantity) => $quantity > 0));
        $selectedServices = $selectedRoomType->services->whereIn('id', $selectedServiceIds);

        foreach ($selectedServices as $service) {
            $quantity = $services[$service->id] ?? 0;
            if ($quantity > 0) {
                $selectedServicesWithQuantity[] = [
                    'id' => $service->id,
                    'name' => $service->name,
                    'price' => $service->price,
                    'quantity' => $quantity,
                ];
            }
        }

        $request->session()->put('selected_services', $selectedServicesWithQuantity);

        return view('clients.bookings.create', [
            'roomType' => $selectedRoomType,
            'checkIn' => $checkIn->toDateString(),
            'checkOut' => $checkOut->toDateString(),
            'totalGuests' => $totalGuests,
            'childrenCount' => $childrenCount,
            'roomQuantity' => $roomQuantity,
            'selectedServices' => $selectedServicesWithQuantity,
            'selectedRooms' => $selectedRooms,
            'availableRoomCount' => $availableRoomCount,
            'days' => $days,
            'basePrice' => $basePrice,
            'discountedPrice' => $discountedPrice,
            'discountAmount' => $discountAmount,
            'serviceTotal' => $serviceTotal,
            'taxFee' => $taxFee,
            'totalPrice' => $totalPrice,
            'user' => $user,
        ]);
    }

    public function confirm(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'check_in' => 'required|date|after_or_equal:today',
                'check_out' => 'required|date|after:check_in',
                'total_guests' => 'required|integer|min:1',
                'children_count' => 'required|integer|min:0',
                'room_type_id' => 'required|exists:room_types,id',
                'room_quantity' => 'required|integer|min:1',
                'special_request' => 'nullable|string',
                'guest.name' => 'required|string|max:255',
                'guest.email' => 'required|email',
                'guest.phone' => 'required|string|regex:/^[0-9]{10,15}$/',
                'guest.country' => 'required|string|max:255',
                'guest.relationship' => 'nullable|string|max:50',
                'services' => 'nullable|array',
                'discount_amount' => 'nullable|numeric|min:0',
                'base_price' => 'required|numeric|min:0',
                'service_total' => 'required|numeric|min:0',
            ]);

            $roomType = RoomType::with('services')->findOrFail($request->room_type_id);
            $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);
            $days = $checkOut->diffInDays($checkIn);

            $basePrice = (float) $request->base_price;
            $discountAmount = (float) $request->discount_amount;
            $totalGuests = (int) $request->total_guests;
            $childrenCount = (int) $request->children_count;
            $roomQuantity = (int) $request->room_quantity;
            $serviceTotal = (float) $request->service_total;
            // dd($basePrice, $discountAmount, $totalGuests, $childrenCount, $roomQuantity, $serviceTotal);
            // Debug dữ liệu services
            \Log::info('Services received in confirm:', ['services' => $request->services ?? []]);

            // Lấy danh sách dịch vụ từ request
            $selectedServices = [];
            $serviceQuantities = [];
            if (!empty($request->services)) {
                // Lấy danh sách id từ mảng services
                $serviceIds = array_map(function ($service) {
                    return $service['id'];
                }, $request->services);

                // Truy vấn lại các dịch vụ từ database
                $selectedServices = $roomType->services->whereIn('id', $serviceIds)->all();

                // Lấy số lượng và giá từ request
                foreach ($request->services as $serviceData) {
                    $serviceId = $serviceData['id'];
                    $quantity = (int) $serviceData['quantity'];
                    $serviceQuantities[$serviceId] = $quantity;
                }

                // Tính lại service_total để đảm bảo chính xác
                $serviceTotal = 0;
                foreach ($selectedServices as $service) {
                    $quantity = $serviceQuantities[$service->id] ?? 1;
                    $serviceTotal += ($service->price ?? 0) * $quantity;
                }
            }

            // Debug selected services
            \Log::info('Selected services in confirm:', ['selectedServices' => $selectedServices, 'serviceQuantities' => $serviceQuantities]);

            if ($discountAmount > 0) {
                $subTotal = ($basePrice - $discountAmount )+ $serviceTotal;
                $taxFee = $subTotal * 0.08; // Thuế 8%
                $totalPrice = $subTotal + $taxFee;
            }else{
                $subTotal = $basePrice + $serviceTotal;
                $taxFee = $subTotal * 0.08; // Thuế 8%
                $totalPrice = $subTotal + $taxFee - $discountAmount;
            }
            // dd($subTotal, $taxFee, $totalPrice);
            // $subTotal = $basePrice + $serviceTotal;
            // $taxFee = $subTotal * 0.08; // Thuế 8%
            // $totalPrice = $subTotal + $taxFee - $discountAmount;

            $guestData = $request->input('guest');
            $paymentSetting = PaymentSetting::first();
            return view('clients.bookings.confirm', [
                'roomType' => $roomType,
                'checkIn' => $checkIn->toDateString(),
                'checkOut' => $checkOut->toDateString(),
                'days' => $days,
                'basePrice' => $basePrice,
                'serviceTotal' => $serviceTotal,
                'subTotal' => $subTotal,
                'taxFee' => $taxFee,
                'totalPrice' => $totalPrice,
                'selectedServices' => $selectedServices,
                'discountAmount' => $discountAmount,
                'totalGuests' => $totalGuests,
                'childrenCount' => $childrenCount,
                'roomQuantity' => $roomQuantity,
                'serviceQuantities' => $serviceQuantities,
                'guestData' => $guestData,
                'deposit_percentage' => $paymentSetting->deposit_percentage,
            ]);
        }

        return redirect()->route('bookings.create')->with('error', 'Vui lòng hoàn tất thông tin đặt phòng trước khi xác nhận.');
    }

    public function calculateDepositAmount($totalAmount)
    {
        $depositPercentage = PaymentSetting::first()->deposit_percentage;
        return $totalAmount * ($depositPercentage / 100);
    }

    public function store(Request $request)
    {
        // dd($request);
        try {
            DB::beginTransaction();

            // Validate dữ liệu từ request
            $validated = $request->validate([
                'check_in' => 'required|date|after_or_equal:today',
                'check_out' => 'required|date|after:check_in',
                'total_guests' => 'required|integer|min:1',
                'children_count' => 'required|integer|min:0',
                'room_type_id' => 'required|exists:room_types,id',
                'room_quantity' => 'required|integer|min:1',
                'special_request' => 'nullable|string',
                'guests.*.name' => 'required|string|max:255',
                'guests.*.email' => 'required|email',
                'guests.*.phone' => 'required|string|regex:/^[0-9]{10,15}$/',
                'guests.*.country' => 'required|string|max:255',
                'guests.*.relationship' => 'nullable|string|max:50',
                'services' => 'nullable|array',
                'discount_amount' => 'nullable|numeric|min:0',
                'payment_method' => 'required|in:cash,online',
                'online_payment_method' => 'required_if:payment_method,online|in:momo,vnpay',
                'base_price' => 'required|numeric|min:0',
                'service_total' => 'required|numeric|min:0',
                'tax_fee' => 'required|numeric|min:0',
                'sub_total' => 'required|numeric|min:0',
                'total_price' => 'required|numeric|min:0',
            ]);

            $data = $request->all();
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đặt phòng.');
            }

            $paymentMethod = $request->input('payment_method');
            $onlinePaymentMethod = $request->input('online_payment_method', null);
            if ($paymentMethod === 'online' && !$onlinePaymentMethod) {
                return redirect()->back()->with('error', 'Vui lòng chọn một cổng thanh toán (MoMo hoặc VNPay).');
            }

            // Debug dữ liệu services nhận được
            \Log::info('Services received in store:', ['services' => $request->services ?? []]);

            // Xử lý thời gian check-in và check-out
            $checkIn = Carbon::parse($validated['check_in'])->setTime(14, 0, 0);
            $checkOut = Carbon::parse($validated['check_out'])->setTime(12, 0, 0);
            $days = $checkOut->diffInDays($checkIn);

            // Lấy thông tin room type
            $roomType = RoomType::findOrFail($validated['room_type_id']);
            $basePrice = (float) $request->input('base_price');
            $discountAmount = (float) $request->input('discount_amount', 0);
            $serviceTotal = (float) $request->input('service_total');
            $taxFee = (float) $request->input('tax_fee');
            $subTotal = (float) $request->input('sub_total');
            $totalPrice = (float) $request->input('total_price');
            // dd($totalPrice, $basePrice, $discountAmount, $serviceTotal, $taxFee, $subTotal);

            // Tạo bản ghi Booking
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
                'status' => 'unpaid',
                'paid_amount' => 0,
            ]);

            if (!empty($validated['services']) && is_array($validated['services'])) {
                foreach ($validated['services'] as $serviceId => $serviceData) {
                    if (!is_array($serviceData) || !isset($serviceData['id'])) {
                        \Log::warning('Dữ liệu dịch vụ không hợp lệ trong store:', ['serviceId' => $serviceId, 'serviceData' => $serviceData]);
                        continue;
                    }

                    $serviceId = (int) $serviceData['id'];
                    $quantity = (int) ($serviceData['quantity'] ?? 1);
                    $price = (float) ($serviceData['price'] ?? 0);

                    if ($serviceId <= 0 || $quantity <= 0) {
                        continue;
                    }

                    \Log::info('Lưu dịch vụ:', [
                        'booking_id' => $booking->id,
                        'room_type_service_id' => $serviceId,
                        'quantity' => $quantity,
                        'price' => $price,
                    ]);

                    BookingRoomTypeService::create([
                        'booking_id' => $booking->id,
                        'room_type_service_id' => $serviceId,
                        'quantity' => $quantity,
                        'price' => $price,
                    ]);
                }
            } else {
                \Log::info('Không có dịch vụ hợp lệ để lưu cho booking:', ['booking_id' => $booking->id]);
            }

            // Xử lý mã giảm giá (promotion)
            if (!empty($data['promotion_id'])) {
                $promotion = Promotion::findOrFail($data['promotion_id']);
                $hasUsedPromotion = DB::table('booking_promotions')
                    ->join('bookings', 'booking_promotions.booking_id', '=', 'bookings.id')
                    ->where('booking_promotions.promotion_id', $promotion->id)
                    ->where('bookings.user_id', Auth::id())
                    ->exists();

                if ($hasUsedPromotion) {
                    DB::rollBack();
                    return redirect()->route('home')->with('error', 'Đã từng sử dụng mã này rồi!');
                }
                if ($promotion->quantity > 0) {
                    $promotion->decrement('quantity');
                    $booking->promotions()->attach($promotion->id);
                } else {
                    DB::rollBack();
                    return redirect()->route('home')->with('error', 'Đã hết mã giảm giá này.');
                }
            }

            // Kiểm tra và gán phòng
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
            $roomQuantity = $validated['room_quantity'];

            if ($roomQuantity > $availableRooms->count()) {
                DB::rollBack();
                return redirect()->route('home')->with('error', 'Không đủ phòng trống để đặt.');
            }

            $selectedRooms = $availableRooms->take($roomQuantity);
            $booking->rooms()->attach($selectedRooms->pluck('id'));

            // Lưu thông tin khách
            $guests = $request->input('guests', []);
            foreach ($guests as $guestData) {
                $guest = Guest::create([
                    'name' => $guestData['name'],
                    'email' => $guestData['email'],
                    'phone' => $guestData['phone'],
                    'country' => $guestData['country'],
                    'relationship' => $guestData['relationship'] ?? 'Người ở chính',
                ]);
                $booking->guests()->attach($guest->id);
            }

            $isPartial = false;
            // dd($totalPrice);
            $depositAmount = $this->calculateDepositAmount($totalPrice);
            if ($request->payment_amount_type == 'partial') {
                $isPartial = true;
                $totalPrice = $depositAmount;
            }

            // Xử lý thanh toán
            $paymentData = [
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'amount' => $totalPrice,
                'status' => 'pending',
                'transaction_id' => null,
                'is_partial' => $isPartial,
            ];

            if ($paymentMethod == 'cash') {
                $paymentData['method'] = 'cash';
                $payment = Payment::create($paymentData);
                $message = 'Đặt phòng của bạn đã hoàn tất! Thông tin chi tiết đã được gửi qua email. Vui lòng thanh toán bằng tiền mặt khi đến nhận phòng.';
                Mail::to($user->email)->send(new BookingSuccess($booking));
                DB::commit();
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
                            DB::rollBack();
                            $booking->delete();
                            $payment->delete();
                            return response()->json([
                                'success' => false,
                                'message' => 'Không thể tạo yêu cầu thanh toán MoMo. Vui lòng thử lại.',
                            ], 400);
                        }
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $booking->delete();
                        $payment->delete();
                        return response()->json([
                            'success' => false,
                            'message' => 'Lỗi khi gọi API MoMo: ' . $e->getMessage(),
                        ], 500);
                    }
                } else if ($onlinePaymentMethod == 'vnpay') {
                    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
                    $vnp_Returnurl = route('bookings.return.vnpay', $booking->id);
                    $vnp_TmnCode = "6Q5Z9DG8";
                    $vnp_HashSecret = "NSEYDYAIT1XETEVUA24DF40DOCMC6NYE";

                    $vnp_TxnRef = $booking->booking_code . '-' . time();
                    $vnp_OrderInfo = 'Thanh toán đặt phòng ' . $booking->booking_code;
                    $vnp_OrderType = 'billpayment';
                    $vnp_Amount = (int) $totalPrice * 100;
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



                    DB::commit();

                    return redirect($vnp_Url);
                }
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::error('Error in store method:', ['exception' => $exception->getMessage()]);
            return redirect()->route('bookings.create')->with('error', $exception->getMessage());
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
            $booking->update(['status' => 'paid']);
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
                $query->with([
                    'amenities' => function ($query) {
                        $query->where('is_active', true);
                    },
                    'rulesAndRegulations' => function ($query) {
                        $query->where('is_active', true);
                    },
                    'services',
                    'roomTypeImages'
                ]);
            },
            'rooms' => function ($query) {
                $query->withTrashed();
            },
            'services.service',
            'payments',
            'guests',
        ])->findOrFail($id);

        $paymentSetting = PaymentSetting::first();
        $deposit_percentage = $paymentSetting->deposit_percentage; 
        $refundPolicies = RefundPolicy::all();

        $title = 'Chi tiết đơn đặt phòng';
        return view('clients.bookings.show', compact('title', 'booking', 'deposit_percentage', 'refundPolicies'));
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

        if ($currentStatus === 'confirmed' && $newStatus === 'cancelled') {
            $currentTime = Carbon::now('Asia/Ho_Chi_Minh');
            $booking->update([
                'status' => $newStatus,
                'actual_check_in' => $currentTime,
                'actual_check_out' => $currentTime,
            ]);
            return redirect()->route('bookings.index')->with('success', 'Hủy đặt phòng thành công!');
        }

        return redirect()->back()->with('error', 'Không thể thay đổi trạng thái từ ' . $currentStatus . ' sang ' . $newStatus . '.');
    }

    public function destroy(string $id): JsonResponse
    {
        $booking = Booking::findOrFail($id);

        // Kiểm tra quyền sở hữu
        if ($booking->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền xóa đơn đặt này!'
            ], 403);
        }

        // Điều kiện: Chỉ cho xóa nếu trạng thái là 'pending' và trước ngày check_in ít nhất 24 giờ
        $now = Carbon::now();
        $checkInDate = Carbon::parse($booking->check_in);
        if ($booking->status !== 'pending' || $checkInDate->diffInHours($now) < 24) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa đơn đặt này vì đã được xác nhận hoặc quá gần ngày nhận phòng!'
            ], 400);
        }

        // Xóa mềm
        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa đơn đặt thành công!'
        ], 200);
    }

    public function checkPromotion(Request $request)
    {
        $promotionCode = $request->input('code');
        $basePrice = (float) $request->input('base_price');
        $serviceTotal = (float) $request->input('service_total');

        $subTotal = $basePrice + $serviceTotal;

        $promotion = Promotion::where('code', $promotionCode)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('quantity', '>', 0)
            ->where('status', 'active')
            ->first();

        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'
            ]);
        }

        if (Auth::check()) {
            $userId = Auth::id();
            $hasUsedPromotion = DB::table('booking_promotions')
                ->join('bookings', 'booking_promotions.booking_id', '=', 'bookings.id')
                ->where('booking_promotions.promotion_id', $promotion->id)
                ->where('bookings.user_id', $userId)
                ->exists();

            if ($hasUsedPromotion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã sử dụng mã giảm giá này trước đây. Mỗi người chỉ được sử dụng mã này một lần.'
                ]);
            }
        }

        $minBookingAmount = (float) $promotion->min_booking_amount;
        $maxDiscountValue = (float) $promotion->max_discount_value;
        $promotionValue = (float) $promotion->value;

        if ($subTotal < $minBookingAmount) {
            return response()->json([
                'success' => false,
                'message' => 'Tổng giá trị đơn hàng chưa đạt mức tối thiểu ' . number_format($minBookingAmount, 0, ',', '.') . ' VND để áp dụng mã này.'
            ]);
        }
        // percent or fixed
        $discountAmount = $promotion->type === 'percent'
            ? $subTotal * ($promotionValue / 100)
            : $promotionValue;

        if ($maxDiscountValue > 0) {
            $discountAmount = min($discountAmount, $maxDiscountValue);
        }

        $discountAmount = min($discountAmount, $subTotal);

        $discountAmount = round($discountAmount, 2);

        $newSubTotal = $subTotal - $discountAmount;

        $taxFee = round($newSubTotal * 0.08, 2);

        $newTotalPrice = round($newSubTotal + $taxFee, 2);

        return response()->json([
            'success' => true,
            'discount_amount' => $discountAmount,
            'new_total_price' => $newTotalPrice,
            'tax_fee' => $taxFee,
            'promotion_id' => $promotion->id,
            'message' => 'Mã giảm giá đã được áp dụng thành công!'
        ]);
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
            return redirect()->route('bookings.show', $id)
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
                        $booking->update(['paid_amount' => $vnp_Amount]);

                        // Kiểm tra xem đây có phải là thanh toán một phần không
                        if ($payment->is_partial) {
                            $booking->update(['status' => 'partial']);
                        } else {
                            $booking->update(['status' => 'paid']);
                        }

                        // Gửi email xác nhận
                        Mail::to($booking->user->email)->send(new BookingSuccess($booking));
                    }
                });

                return redirect()->route('bookings.show', $id)
                    ->with('success', 'Thanh toán thành công! Thông tin đặt phòng đã được gửi qua email.');
            } catch (\Throwable $th) {
                return redirect()->route('bookings.show', $id)
                    ->with('error', 'Đã có lỗi xảy ra trong quá trình cập nhật thanh toán: ' . $th->getMessage());
            }
        } else {
            return redirect()->route('bookings.show', $id)
                ->with('error', 'Thanh toán không thành công! Mã lỗi: ' . $vnp_ResponseCode);
        }
    }

    public function processNextPayment(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'payment_method' => 'required|in:momo,vnpay',
            'payment_amount_type' => 'required|in:full,partial'
        ]);

        $paymentAmount = $booking->total_price;
        $deposit_percentage = $request->input('deposit_percentage');
        if ($validated['payment_amount_type'] == 'partial') {
            $paymentAmount = $paymentAmount * (intval($deposit_percentage) / 100);
        }

        $paymentData = [
            'user_id' => $booking->user_id,
            'booking_id' => $booking->id,
            'amount' => $paymentAmount,
            'status' => 'pending',
            'transaction_id' => null,
            'is_partial' => $validated['payment_amount_type'] == 'partial',
        ];

        $paymentMethod = $validated['payment_method'];
        if ($paymentMethod == 'vnpay') {
            $payment = Payment::create($paymentData);
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = route('bookings.return.vnpay', $booking->id);
            $vnp_TmnCode = "6Q5Z9DG8";
            $vnp_HashSecret = "NSEYDYAIT1XETEVUA24DF40DOCMC6NYE";

            $vnp_TxnRef = $booking->booking_code . '-' . time();
            $vnp_OrderInfo = 'Thanh toán đặt phòng ' . $booking->booking_code;
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = (int) $paymentAmount * 100;
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

            DB::commit();

            return redirect($vnp_Url);
        }
    }
}
