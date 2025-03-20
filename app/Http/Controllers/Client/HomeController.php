<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\Faq;
use App\Models\About;
use App\Models\Booking;
use App\Models\Service;
use App\Models\RoomType;
use App\Models\Introduction;
use Illuminate\Http\Request;
use App\Helpers\FormatHelper;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Tính số phòng còn trống cho một loại phòng
     */
    private function calculateAvailableRooms(RoomType $roomType, $checkIn, $checkOut)
    {

        // Tổng số phòng của loại phòng này
        $totalRooms = $roomType->rooms->count();

        // Số phòng đã được đặt trong khoảng thời gian
        $bookedRooms = Booking::whereHas('rooms', function ($query) use ($roomType) {
            $query->where('room_type_id', $roomType->id);
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

        // Số phòng còn trống
        return max(0, $totalRooms - $bookedRooms);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Lấy dữ liệu từ form tìm kiếm
        $checkIn = $request->input('check_in', Carbon::today()->format('Y-m-d'));
        $checkOut = $request->input('check_out', Carbon::tomorrow()->format('Y-m-d'));
        $totalGuests = (int) $request->input('total_guests', 2);
        $childrenCount = (int) $request->input('children_count', 0);

        // Validate dữ liệu
        try {
            $checkInDate = Carbon::parse($checkIn);
            $checkOutDate = Carbon::parse($checkOut);

            if ($checkInDate->greaterThanOrEqualTo($checkOutDate)) {
                return redirect()->route('home')->with('error', 'Ngày trả phòng phải sau ngày nhận phòng.');
            }

            if ($checkInDate->lessThan(Carbon::today())) {
                return redirect()->route('home')->with('error', 'Ngày nhận phòng không được trước ngày hiện tại.');
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Ngày nhận phòng hoặc trả phòng không hợp lệ.');
        }

        // Tính tổng số khách
        $totalPeople = $totalGuests + $childrenCount;

        // Lấy danh sách loại phòng còn trống
        $roomTypes = RoomType::with(['rooms', 'amenities', 'roomTypeImages'])
            ->where('is_active', true)
            ->where('max_capacity', '>=', $totalPeople)
           ->paginate(4);
        // $roomTypes = collect($roomTypes);

        // Tính số phòng còn trống cho từng loại phòng
        $roomTypes = $roomTypes->map(function ($roomType) use ($checkIn, $checkOut) {
            $roomType->available_rooms = $this->calculateAvailableRooms($roomType, $checkIn, $checkOut);
            return $roomType;
        })->filter(function ($roomType) {
            return $roomType->available_rooms > 0;
        });

        return view('clients.home', compact('roomTypes', 'checkIn', 'checkOut', 'totalGuests', 'childrenCount'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $roomType = RoomType::with(['rooms', 'amenities', 'roomTypeImages', 'services', 'rulesAndRegulations'])
            ->where('is_active', true)
            ->findOrFail($id);

        // Lấy dữ liệu từ request
        $checkIn = $request->input('check_in', Carbon::today()->format('Y-m-d'));
        $checkOut = $request->input('check_out', Carbon::tomorrow()->format('Y-m-d'));
        $totalGuests = (int) $request->input('total_guests', 2);
        $childrenCount = (int) $request->input('children_count', 0);

        $checkInFormatted = FormatHelper::FormatDateVI($checkIn);
        $checkOutFormatted = FormatHelper::FormatDateVI($checkOut);
        // Tính số phòng còn trống
        $roomType->available_rooms = $this->calculateAvailableRooms($roomType, $checkIn, $checkOut);

        if ($roomType->available_rooms == 0) {
            return redirect()->route('home')->with('error', 'Phòng này hiện không còn trống trong khoảng thời gian bạn chọn.');
        }

        return view('clients.room.detail', compact('roomType', 'checkIn', 'checkOut', 'totalGuests', 'childrenCount'));
    }

    /**
     * Display FAQs
     */
    public function faqs()
    {
        $faqs = Faq::where('is_active', 1)->get();
        return view('clients.faq', compact('faqs'));
    }

    /**
     * Display Services
     */
    public function services()
    {
        $services = Service::where('is_active', 1)->get();
        return view('clients.service', compact('services'));
    }

    /**
     * Display About
     */
    public function abouts()
    {
        $about = About::where('is_use', 1)->first() ?? new About(['about' => 'Chưa có nội dung nào được thiết lập.']);
        return view('clients.about', compact('about'));
    }

    /**
     * Display Introduction
     */
    public function introductions()
    {
        $introduction = Introduction::where('is_use', 1)->first() ?? new Introduction(['introduction' => 'Chưa có nội dung nào được thiết lập.']);
        return view('clients.introduction', compact('introduction'));
    }
}
