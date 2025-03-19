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
    /**
     * Display a listing of the resource.
     */
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

        foreach ($bookings as $booking) {
            if ($booking->rooms->first() && $booking->rooms->first()->roomType) {
                $mainImage = $booking->rooms->first()->roomType->roomTypeImages->where('is_main', true)->first();
                if ($mainImage) {
                    Log::info('Image URL for booking ' . $booking->id . ': ' . $mainImage->image);
                    Log::info('Full Image URL: ' . Storage::url($mainImage->image));
                } else {
                    Log::info('No main image found for booking ' . $booking->id);
                }
            }
        }

        return view('clients.bookings.index', compact('bookings', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đặt phòng.');
        }

        // Lấy dữ liệu từ request
        $roomTypeId = $request->input('room_type_id');
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');
        $totalGuests = (int) $request->input('total_guests', 2);
        $childrenCount = (int) $request->input('children_count', 0);
        $roomQuantity = (int) $request->input('room_quantity', 1);
        $services = $request->input('services', []);

        // Validate dữ liệu
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'total_guests' => 'required|integer|min:1',
            'children_count' => 'required|integer|min:0',
            'room_quantity' => 'required|integer|min:1',
        ]);

        // Chuẩn hóa định dạng ngày tháng
        $checkIn = Carbon::parse($checkIn)->format('Y-m-d');
        $checkOut = Carbon::parse($checkOut)->format('Y-m-d');

        // Lấy thông tin loại phòng
        $selectedRoomType = RoomType::with(['amenities', 'services', 'roomTypeImages'])->findOrFail($roomTypeId);

        // Kiểm tra số phòng còn trống
        $totalRooms = $selectedRoomType->rooms->count();
        $bookedRooms = Booking::whereHas('rooms', function ($query) use ($selectedRoomType) {
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
        ->count();

        $availableRooms = max(0, $totalRooms - $bookedRooms);
        if ($roomQuantity > $availableRooms) {
            return redirect()->route('home')->with('error', 'Số lượng phòng yêu cầu vượt quá số phòng còn trống.');
        }

        // Truyền dữ liệu vào view checkout
        return view('clients.bookings.create', compact(
            'selectedRoomType',
            'checkIn',
            'checkOut',
            'totalGuests',
            'childrenCount',
            'roomQuantity',
            'services'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'total_guests' => 'required|integer|min:1',
            'children_count' => 'required|integer|min:0',
            'room_type_id' => 'required|exists:room_types,id',
            'special_request' => 'nullable|string',
            'guests' => 'required|array|min:1', // Danh sách người ở
            'guests.*.name' => 'required|string|max:255',
            'guests.*.id_number' => 'nullable|string|regex:/^[0-9]{9,12}$/',
            'guests.*.birth_date' => 'nullable|date|before:today',
            'guests.*.gender' => 'nullable|in:male,female,other',
            'guests.*.phone' => 'nullable|string|regex:/^[0-9]{10,15}$/',
            'guests.*.email' => 'nullable|email',
            'guests.*.relationship' => 'nullable|string|max:50',
        ]);

        // Lấy thông tin người đặt (User)
        $user = Auth::user();
        if (!$user) {
            // Nếu chưa đăng nhập, tạo User mới (giả định)
            $user = User::create([
                'name' => $request->input('user_name'), // Giả định có trường này trong form
                'email' => $request->input('user_email'),
                'password' => bcrypt($request->input('user_password')),
                'phone' => $request->input('user_phone'),
            ]);
        }

        // Chuẩn hóa định dạng DATETIME cho check_in và check_out
        $checkIn = Carbon::parse($validated['check_in'])->setTime(14, 0, 0); // Nhận phòng lúc 14:00
        $checkOut = Carbon::parse($validated['check_out'])->setTime(12, 0, 0); // Trả phòng lúc 12:00

        // Tạo Booking
        $booking = Booking::create([
            'booking_code' => 'BOOK' . time(),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'total_price' => $request->total_price,
            'total_guests' => $validated['total_guests'],
            'children_count' => $validated['children_count'],
            'user_id' => $user->id,
            'room_type_id' => $validated['room_type_id'],
            'special_request' => $request->special_request, // Sử dụng special_request từ textarea
            'service_plus_status' => $request->service_plus_status,
        ]);

        // Tạo danh sách Guest và liên kết với Booking
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

        // Liên kết Guests với Booking
        $booking->guests()->attach($guestIds);

        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'Đặt phòng thành công!');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
        return view('clients.bookings.show', compact('title', 'booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $booking = Booking::with(['rooms', 'rooms.roomType', 'servicePlus'])->findOrFail($id);
        return view('clients.bookings.edit', compact('booking'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);
        $currentStatus = $booking->status;
        $newStatus = $request->input('status');

        if ($currentStatus === 'confirmed' && $newStatus === 'cancelled') {
            $booking->update(['status' => $newStatus]);
            return redirect()->route('client.bookings.index')->with('success', 'Hủy đặt phòng thành công!');
        }

        return redirect()->back()->with('error', 'Không thể thay đổi trạng thái từ ' . $currentStatus . ' sang ' . $newStatus . '.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        if ($booking->user_id === Auth::id()) {
            $booking->delete(); // Xóa mềm
            return redirect()->route('client.bookings.index')->with('success', 'Xóa đơn đặt thành công!');
        }
        return redirect()->back()->with('error', 'Bạn không có quyền xóa đơn đặt này!');
    }
}
