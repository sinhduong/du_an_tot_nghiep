<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Guest;
use App\Models\Booking;
use App\Models\RoomType;
use App\Models\Promotion;
use App\Models\ServicePlus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

        $roomTypeId = $request->input('room_type_id');
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');
        $totalGuests = (int) $request->input('total_guests', 2);
        $childrenCount = (int) $request->input('children_count', 0);
        $roomQuantity = (int) $request->input('room_quantity', 1);
        $services = $request->input('services', []);

        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'total_guests' => 'required|integer|min:1',
            'children_count' => 'required|integer|min:0',
            'room_quantity' => 'required|integer|min:1',
        ]);

        $checkIn = Carbon::parse($checkIn);
        $checkOut = Carbon::parse($checkOut);
        $days = $checkOut->diffInDays($checkIn);

        $selectedRoomType = RoomType::with(['amenities', 'services', 'roomTypeImages', 'rooms'])->findOrFail($roomTypeId);

        $basePrice = $selectedRoomType->price * $roomQuantity * $days;
        $serviceTotal = 0;
        if (!empty($services)) {
            foreach ($selectedRoomType->services->whereIn('id', $services) as $service) {
                $quantity = $request->input("service_quantity_{$service->id}", 1);
                $serviceTotal += $service->price * $quantity;
            }
        }
        $subTotal = $basePrice + $serviceTotal;
        $taxFee = $subTotal * 0.08;
        $totalPrice = $subTotal + $taxFee;

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
            'serviceTotal',
            'subTotal',
            'taxFee',
            'totalPrice'
        ));
    }
    public function confirm(Request $request)
    {
        if ($request->isMethod('post')) {
            // Validate dữ liệu từ create.blade.php
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

            // Lấy thông tin từ request
            $roomType = RoomType::with('services')->findOrFail($request->room_type_id);
            $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);
            $days = $checkOut->diffInDays($checkIn);
            $basePrice = $request->base_price; // Lấy từ hidden input trong create
            $serviceTotal = $request->service_total; // Lấy từ hidden input trong create
            $discountAmount = $request->discount_amount;

            // Tính toán lại để đảm bảo chính xác
            $subTotal = $basePrice + $serviceTotal;
            $taxFee = $subTotal * 0.08; // Thuế 8%
            $totalPrice = $subTotal - $discountAmount + $taxFee;

            // Lấy danh sách dịch vụ bổ sung từ request
            $selectedServices = [];
            if (!empty($request->services)) {
                $selectedServices = $roomType->services->whereIn('id', $request->services)->all();
            }

            // Truyền dữ liệu sang confirm.blade.php
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
                'discountAmount'
            ));
        }

        return redirect()->route('bookings.create')->with('error', 'Không thể truy cập trực tiếp trang này.');
    }

    public function store(Request $request)
    {
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
            'guests.*.birth_date' => 'nullable|date|before:today',
            'guests.*.gender' => 'nullable|in:male,female,other',
            'guests.*.phone' => 'nullable|string|regex:/^[0-9]{10,15}$/',
            'guests.*.email' => 'nullable|email',
            'guests.*.relationship' => 'nullable|string|max:50',
            'services' => 'nullable|array',
            'service_quantity_*' => 'nullable|integer|min:1',
            'discount_amount' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:on_site,online',
            'base_price' => 'required|numeric|min:0',
            'service_total' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đặt phòng.');
        }

        $checkIn = Carbon::parse($validated['check_in'])->setTime(14, 0, 0);
        $checkOut = Carbon::parse($validated['check_out'])->setTime(12, 0, 0);

        $roomType = RoomType::findOrFail($validated['room_type_id']);
        $days = $checkOut->diffInDays($checkIn);

        // Lấy giá trị từ request
        $basePrice = $request->input('base_price');
        $serviceTotal = $request->input('service_total');
        $discountAmount = $request->input('discount_amount', 0);

        // Tính lại để đảm bảo chính xác
        $subTotal = $basePrice + $serviceTotal;
        $taxFee = $subTotal * 0.08; // Thuế 8%
        $totalPrice = $subTotal - $discountAmount + $taxFee;

        // Tạo booking
        $booking = Booking::create([
            'booking_code' => 'BOOK' . time(),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'total_price' => $totalPrice, // Sử dụng totalPrice đã tính lại
            'total_guests' => $validated['total_guests'],
            'children_count' => $validated['children_count'],
            'user_id' => $user->id,
            'room_type_id' => $validated['room_type_id'],
            'special_request' => $request->input('special_request'),
            'service_plus_status' => !empty($validated['services']) ? 'not_yet_paid' : 'none',
            'discount_amount' => $discountAmount,
            'payment_method' => $validated['payment_method'],
            'status' => $validated['payment_method'] == 'on_site' ? 'pending_confirmation' : 'awaiting_payment',
        ]);

        // Kiểm tra phòng trống
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

        // Gắn thông tin khách
        $guestIds = [];
        foreach ($validated['guests'] as $guestData) {
            $guest = Guest::create([
                'name' => $guestData['name'],
                'id_number' => $guestData['id_number'] ?? null,
                'birth_date' => $guestData['birth_date'] ?? null,
                'gender' => $guestData['gender'] ?? null,
                'phone' => $guestData['phone'] ?? null,
                'email' => $guestData['email'] ?? null,
                'relationship' => $guestData['relationship'] ?? null,
            ]);
            $guestIds[] = $guest->id;
        }
        $booking->guests()->attach($guestIds);

        if ($validated['payment_method'] == 'online') {
            $booking->update(['status' => 'confirmed']);
        }

        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'Đặt phòng đã được hoàn tất!');
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
        $totalPrice = $request->input('total_price');

        $promotion = Promotion::where('code', $promotionCode)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if ($promotion) {
            $discountAmount = $promotion->discount_type === 'percentage'
                ? $totalPrice * ($promotion->discount_value / 100)
                : $promotion->discount_value;

            return response()->json([
                'success' => true,
                'discount_amount' => $discountAmount,
                'message' => 'Mã giảm giá hợp lệ!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'
        ]);
    }
}
