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
        $checkInDate = Carbon::parse($checkIn)->startOfDay();
        $checkOutDate = Carbon::parse($checkOut)->endOfDay();

        // Tổng số phòng của loại phòng này với trạng thái 'available'
        $totalRooms = $roomType->rooms()->where('status', 'available')->count();

        // Số phòng đã được đặt trong khoảng thời gian
        $bookedRooms = Booking::whereHas('rooms', function ($query) use ($roomType) {
            $query->where('room_type_id', $roomType->id);
        })
            ->where(function ($query) use ($checkInDate, $checkOutDate) {
                $query->where(function ($q) use ($checkInDate, $checkOutDate) {
                    $q->whereBetween('check_in', [$checkInDate, $checkOutDate])
                        ->orWhereBetween('check_out', [$checkInDate, $checkOutDate])
                        ->orWhere(function ($inner) use ($checkInDate, $checkOutDate) {
                            $inner->where('check_in', '<=', $checkInDate)
                                ->where('check_out', '>=', $checkOutDate);
                        });
                })
                // Chỉ tính các booking chưa trả phòng (actual_check_out = null) hoặc trả phòng sau ngày check_in yêu cầu
                ->where(function ($q) use ($checkInDate) {
                    $q->whereNull('actual_check_out')
                        ->orWhere('actual_check_out', '>', $checkInDate);
                })
                // Loại bỏ các booking đã bị hủy hoặc hoàn tiền
                ->whereNotIn('status', ['cancelled', 'refunded']);
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
        $roomCount = (int) $request->input('room_count', 1);

        // Validate input dates
        try {
            $checkInDate = Carbon::parse($checkIn);
            $checkOutDate = Carbon::parse($checkOut);

            if ($checkInDate->lt(Carbon::today())) {
                return back()->with('error', 'Ngày nhận phòng không thể là ngày trong quá khứ.');
            }
            if ($checkInDate->gte($checkOutDate)) {
                return back()->with('error', 'Ngày trả phòng phải sau ngày nhận phòng.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Ngày không hợp lệ.');
        }

        // Tính tổng số người
        $totalPeople = $totalGuests + $childrenCount;

        // Lấy tất cả các loại phòng thỏa mãn điều kiện
        $roomTypes = RoomType::query()
            ->with(['roomTypeImages', 'amenities', 'rooms'])
            ->where('is_active', true)
            ->where('max_capacity', '>=', $totalPeople)
            ->where('children_free_limit', '>=', $childrenCount)
            ->get();

        // Lọc các loại phòng dựa trên số phòng còn trống
        $roomTypes = $roomTypes->filter(function ($roomType) use ($checkInDate, $checkOutDate, $roomCount) {
            $availableRooms = $this->calculateAvailableRooms($roomType, $checkInDate, $checkOutDate);
            $roomType->available_rooms = $availableRooms;
            return $availableRooms >= $roomCount;
        });

        // Truyền dữ liệu sang view
        return view('clients.home', compact('roomTypes', 'checkIn', 'checkOut', 'totalGuests', 'childrenCount', 'roomCount'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        // Lấy RoomType theo ID
        $roomType = RoomType::with(['roomTypeImages', 'amenities', 'services', 'rulesAndRegulations', 'rooms'])
            ->where('is_active', true)
            ->findOrFail($id);

        // Lấy dữ liệu từ query string
        $checkIn = $request->input('check_in', Carbon::today()->format('Y-m-d'));
        $checkOut = $request->input('check_out', Carbon::tomorrow()->format('Y-m-d'));
        $totalGuests = (int) $request->input('total_guests', 2);
        $childrenCount = (int) $request->input('children_count', 0);
        $roomCount = (int) $request->input('room_count', 1);

        // Validate input dates
        try {
            $checkInDate = Carbon::parse($checkIn);
            $checkOutDate = Carbon::parse($checkOut);

            if ($checkInDate->lt(Carbon::today())) {
                return back()->with('error', 'Ngày nhận phòng không thể là ngày trong quá khứ.');
            }
            if ($checkInDate->gte($checkOutDate)) {
                return back()->with('error', 'Ngày trả phòng phải sau ngày nhận phòng.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Ngày không hợp lệ.');
        }

        // Tính tổng số người
        $totalPeople = $totalGuests + $childrenCount;

        // Kiểm tra max_capacity và children_free_limit
        if ($totalPeople > $roomType->max_capacity) {
            return back()->with('error', 'Tổng số người vượt quá sức chứa tối đa của loại phòng này.');
        }

        if ($childrenCount > $roomType->children_free_limit) {
            $request->session()->flash('warning', "Số trẻ em vượt quá giới hạn miễn phí ({$roomType->children_free_limit}). Phí bổ sung có thể được áp dụng.");
        }

        // Tính số phòng còn trống
        $availableRooms = $this->calculateAvailableRooms($roomType, $checkInDate, $checkOutDate);
        $roomType->available_rooms = $availableRooms;

        // Kiểm tra số phòng yêu cầu
        if ($roomCount > $availableRooms) {
            return back()->with('error', "Không đủ số phòng còn trống. Hiện tại chỉ còn {$availableRooms} phòng.");
        }

        // Truyền dữ liệu sang view
        return view('clients.room.detail', compact('roomType', 'checkIn', 'checkOut', 'totalGuests', 'childrenCount', 'roomCount'));
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
