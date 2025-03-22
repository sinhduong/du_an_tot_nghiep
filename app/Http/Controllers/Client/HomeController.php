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
                ->where(function ($q) use ($checkInDate) {
                    $q->whereNull('actual_check_out')
                        ->orWhere('actual_check_out', '>=', $checkInDate);
                })
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
        // Đặt múi giờ cho Carbon
        Carbon::setLocale('vi');
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        // Lấy dữ liệu từ form tìm kiếm
        $checkIn = $request->input('check_in', Carbon::today()->format('Y-m-d'));
        $checkOut = $request->input('check_out', Carbon::tomorrow()->format('Y-m-d'));
        $totalGuests = (int) $request->input('total_guests', 2);
        $childrenCount = (int) $request->input('children_count', 0);
        $roomCount = (int) $request->input('room_count', 1);

        // Xử lý ngày giờ
        try {
            $checkInDate = Carbon::parse($checkIn);
            $checkOutDate = Carbon::parse($checkOut);
            $now = Carbon::now();

            // Nếu ngày check-in là quá khứ hoặc hôm nay sau 22:00, điều chỉnh sang ngày hôm sau
            if ($checkInDate->lt($now->startOfDay()) || ($checkInDate->isToday() && $now->hour >= 22)) {
                $checkInDate = $now->copy()->addDay()->startOfDay();
                $checkOutDate = $checkInDate->copy()->addDay();
                $checkIn = $checkInDate->format('Y-m-d');
                $checkOut = $checkOutDate->format('Y-m-d');
                $request->session()->flash('info', 'Đặt phòng vào thời điểm này sẽ được check-in từ ngày mai (' . $checkInDate->format('d/m/Y') . ').');
            }

            // Kiểm tra check-out phải sau check-in
            if ($checkInDate->gte($checkOutDate)) {
                $checkOutDate = $checkInDate->copy()->addDay();
                $checkOut = $checkOutDate->format('Y-m-d');
                $request->session()->flash('info', 'Ngày trả phòng đã được điều chỉnh để sau ngày nhận phòng.');
            }

            // Định dạng ngày để hiển thị tiếng Việt
            $days = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
            $months = [
                'tháng 1', 'tháng 2', 'tháng 3', 'tháng 4', 'tháng 5', 'tháng 6',
                'tháng 7', 'tháng 8', 'tháng 9', 'tháng 10', 'tháng 11', 'tháng 12'
            ];
            $startDay = $days[$checkInDate->dayOfWeek];
            $startDateNum = $checkInDate->day;
            $startMonth = $months[$checkInDate->month - 1];
            $endDay = $days[$checkOutDate->dayOfWeek];
            $endDateNum = $checkOutDate->day;
            $endMonth = $months[$checkOutDate->month - 1];
            $formattedDateRange = "{$startDay}, {$startDateNum} {$startMonth} - {$endDay}, {$endDateNum} {$endMonth}";

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
        })->values();

        // Truyền dữ liệu sang view
        return view('clients.home', compact('roomTypes', 'checkIn', 'checkOut', 'totalGuests', 'childrenCount', 'roomCount', 'formattedDateRange'));
    }
    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        // Đặt múi giờ cho Carbon
        Carbon::setLocale('vi');
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        // Lấy thông tin loại phòng
        $roomType = RoomType::with(['roomTypeImages', 'amenities', 'services', 'rulesAndRegulations'])->findOrFail($id);

        // Lấy dữ liệu từ request
        $checkIn = $request->input('check_in', Carbon::today()->format('Y-m-d'));
        $checkOut = $request->input('check_out', Carbon::tomorrow()->format('Y-m-d'));
        $totalGuests = (int) $request->input('total_guests', 2);
        $childrenCount = (int) $request->input('children_count', 0);
        $roomCount = (int) $request->input('room_quantity', 1);

        // Xử lý ngày giờ
        try {
            $checkInDate = Carbon::parse($checkIn);
            $checkOutDate = Carbon::parse($checkOut);
            $now = Carbon::now();

            // Nếu ngày check-in là quá khứ hoặc hôm nay sau 22:00, điều chỉnh sang ngày hôm sau
            if ($checkInDate->lt($now->startOfDay()) || ($checkInDate->isToday() && $now->hour >= 22)) {
                $checkInDate = $now->copy()->addDay()->startOfDay();
                $checkOutDate = $checkInDate->copy()->addDay();
                $checkIn = $checkInDate->format('Y-m-d');
                $checkOut = $checkOutDate->format('Y-m-d');
                $request->session()->flash('warning', 'Đặt phòng vào thời điểm này sẽ được check-in từ ngày mai (' . $checkInDate->format('d/m/Y') . ').');
            }

            // Kiểm tra check-out phải sau check-in
            if ($checkInDate->gte($checkOutDate)) {
                $checkOutDate = $checkInDate->copy()->addDay();
                $checkOut = $checkOutDate->format('Y-m-d');
                $request->session()->flash('warning', 'Ngày trả phòng đã được điều chỉnh để sau ngày nhận phòng.');
            }

            // Định dạng ngày để hiển thị tiếng Việt
            $days = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
            $months = [
                'tháng 1', 'tháng 2', 'tháng 3', 'tháng 4', 'tháng 5', 'tháng 6',
                'tháng 7', 'tháng 8', 'tháng 9', 'tháng 10', 'tháng 11', 'tháng 12'
            ];
            $startDay = $days[$checkInDate->dayOfWeek];
            $startDateNum = $checkInDate->day;
            $startMonth = $months[$checkInDate->month - 1];
            $endDay = $days[$checkOutDate->dayOfWeek];
            $endDateNum = $checkOutDate->day;
            $endMonth = $months[$checkOutDate->month - 1];
            $formattedDateRange = "{$startDay}, {$startDateNum} {$startMonth} - {$endDay}, {$endDateNum} {$endMonth}";

        } catch (\Exception $e) {
            return back()->with('error', 'Ngày không hợp lệ.');
        }

        // Tính số phòng còn trống (giả sử bạn có logic tương tự như HomeController)
        $roomType->available_rooms = $this->calculateAvailableRooms($roomType, $checkInDate, $checkOutDate);

        // Truyền dữ liệu sang view
        return view('clients.room.detail', compact('roomType', 'checkIn', 'checkOut', 'totalGuests', 'childrenCount', 'roomCount', 'formattedDateRange'));
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
