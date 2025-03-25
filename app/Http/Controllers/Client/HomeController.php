<?php

namespace App\Http\Controllers\Client;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Log;
use Carbon\Carbon;
use App\Models\Faq;
use App\Models\About;
use App\Models\Booking;
use App\Models\Service;
use App\Models\RoomType;
use App\Models\Promotion;
use App\Models\Introduction;
use Illuminate\Http\Request;
use App\Helpers\FormatHelper;
use App\Http\Controllers\Controller;
use App\Models\System;

class HomeController extends Controller
{
    /**
     * Tính số phòng còn trống cho một loại phòng
     */
    private function calculateAvailableRooms(RoomType $roomType, $checkIn, $checkOut)
    {
        $checkInDate = Carbon::parse($checkIn)->startOfDay();
        $checkOutDate = Carbon::parse($checkOut)->endOfDay();

        $totalRooms = $roomType->rooms()->where('status', 'available')->count();

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

        return max(0, $totalRooms - $bookedRooms);
    }


    public function index(Request $request)
    {
        Carbon::setLocale('vi');
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $checkIn = $request->input('check_in', Carbon::today()->setHour(14)->setMinute(0)->setSecond(0)->toDateTimeString());
        $checkOut = $request->input('check_out', Carbon::tomorrow()->setHour(12)->setMinute(0)->setSecond(0)->toDateTimeString());
        $totalGuests = (int) $request->input('total_guests', 2);
        $childrenCount = (int) $request->input('children_count', 0);
        $roomCount = (int) $request->input('room_count', 1);

        try {
            $checkInDate = Carbon::parse($checkIn);
            $checkOutDate = Carbon::parse($checkOut);
            $now = Carbon::now();

            if ($checkInDate->lt($now) || ($checkInDate->isToday() && $now->hour >= 21)) {
                $checkInDate = $now->copy()->addDay()->setHour(14)->setMinute(0)->setSecond(0);
                $checkOutDate = $checkInDate->copy()->addDay()->setHour(12)->setMinute(0)->setSecond(0);
                $checkIn = $checkInDate->toDateTimeString();
                $checkOut = $checkOutDate->toDateTimeString();
            }

            if ($checkInDate->gte($checkOutDate)) {
                $checkOutDate = $checkInDate->copy()->addDay()->setHour(12)->setMinute(0)->setSecond(0);
                $checkOut = $checkOutDate->toDateTimeString();
                $request->session()->flash('info', 'Ngày trả phòng đã được điều chỉnh để sau ngày nhận phòng.');
            }

            $nights = $checkInDate->diffInDays($checkOutDate);
            if ($nights < 1) $nights = 1;

            $days = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
            $months = ['tháng 1', 'tháng 2', 'tháng 3', 'tháng 4', 'tháng 5', 'tháng 6', 'tháng 7', 'tháng 8', 'tháng 9', 'tháng 10', 'tháng 11', 'tháng 12'];
            $startDay = $days[$checkInDate->dayOfWeek];
            $startDateNum = $checkInDate->day;
            $startMonth = $months[$checkInDate->month - 1];
            $startTime = $checkInDate->format('H:i');
            $endDay = $days[$checkOutDate->dayOfWeek];
            $endDateNum = $checkOutDate->day;
            $endMonth = $months[$checkOutDate->month - 1];
            $endTime = $checkOutDate->format('H:i');
            $formattedDateRange = "{$startDay}, {$startDateNum} {$startMonth} {$startTime} - {$endDay}, {$endDateNum} {$endMonth} {$endTime}";
        } catch (\Exception $e) {
            return back()->with('error', 'Ngày giờ không hợp lệ.');
        }

        $totalPeople = $totalGuests + $childrenCount;

        $roomTypes = RoomType::query()
            ->with(['roomTypeImages', 'amenities', 'rooms', 'promotions'])
            ->where('is_active', true)
            ->where('max_capacity', '>=', $totalPeople)
            ->where('children_free_limit', '>=', $childrenCount)
            ->get();

        $roomTypes = $roomTypes->filter(function ($roomType) use ($checkInDate, $checkOutDate, $roomCount, $nights, $now) {
            $availableRooms = $this->calculateAvailableRooms($roomType, $checkInDate, $checkOutDate);
            $roomType->available_rooms = $availableRooms;

            // Tính giá gốc
            $roomType->total_original_price = $roomType->price * $nights * $roomCount;

            // Lấy tất cả mã giảm giá của loại phòng (chỉ percent)
            $promotions = $roomType->promotions()
                ->where('status', 'active')
                ->where('type', 'percent')
                ->whereDate('start_date', '<=', $now->toDateString())
                ->whereDate('end_date', '>=', $now->toDateString())
                ->where('quantity', '>', 0)
                ->get();

            $bestPromotion = null;
            $bestDiscountedPrice = $roomType->total_original_price;

            foreach ($promotions as $promotion) {
                $discountedPrice = $this->calculateDiscountedPrice($roomType->price, $promotion, $nights, $roomCount);
                if ($discountedPrice < $bestDiscountedPrice) {
                    $bestDiscountedPrice = $discountedPrice;
                    $bestPromotion = $promotion;
                }
            }

            $roomType->total_discounted_price = $bestDiscountedPrice;
            $roomType->discounted_price_per_night = $bestPromotion ? ($roomType->total_discounted_price / ($nights * $roomCount)) : $roomType->price;
            $roomType->promotion_info = $bestPromotion ? [
                'code' => $bestPromotion->code,
                'value' => $bestPromotion->value,
                'type' => 'percent',
            ] : null;

            // Debug để kiểm tra giá
            \Log::info("Room: {$roomType->name}, Price: {$roomType->price}, Nights: $nights, RoomCount: $roomCount, Original: {$roomType->total_original_price}, Discounted: {$roomType->total_discounted_price}, Promotion: " . json_encode($bestPromotion ? $bestPromotion->toArray() : null));

            return $availableRooms >= $roomCount;
        })->values();

        $promotions = Promotion::where('status', 'active')
            ->where('type', 'percent')
            ->where('end_date', '>=', now())
            ->get();

        // lấy thông tin, dữ liệu system 
        $systems = System::orderBy('id', 'desc')->first(); // Lấy bản ghi mới nhất
        return view('clients.home', compact('roomTypes', 'checkIn', 'checkOut', 'totalGuests', 'childrenCount', 'roomCount', 'formattedDateRange', 'nights', 'promotions','systems'));
    }

    private function calculateDiscountedPrice($originalPrice, $promotion, $nights, $roomCount)
    {
        $totalPrice = $originalPrice * $nights * $roomCount;
        if (!$promotion || $promotion->status !== 'active' || $promotion->type !== 'percent') {
            return $totalPrice;
        }

        if ($promotion->min_booking_amount && $totalPrice < $promotion->min_booking_amount) {
            \Log::info("Total price $totalPrice does not meet min_booking_amount {$promotion->min_booking_amount}");
            return $totalPrice;
        }

        $discount = $totalPrice * ($promotion->value / 100);
        if ($promotion->max_discount_value && $discount > $promotion->max_discount_value) {
            $discount = $promotion->max_discount_value;
        }
        $discountedPrice = $totalPrice - $discount;

        return max(0, $discountedPrice);
    }
    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        // Đặt múi giờ cho Carbon
        Carbon::setLocale('vi');
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        // Lấy thông tin loại phòng
        $roomType = RoomType::with([
            'roomTypeImages',
            'amenities' => function ($query) {
                $query->where('is_active', true);
            },
            'services' => function ($query) {
                $query->where('is_active', true);
            },
            'rulesAndRegulations' => function ($query) {
                $query->where('is_active', true);
            },
            'promotions'
        ])->findOrFail($id);

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

            // Tính số đêm
            $nights = $checkInDate->diffInDays($checkOutDate);

            // Định dạng ngày để hiển thị tiếng Việt
            $days = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
            $months = [
                'tháng 1',
                'tháng 2',
                'tháng 3',
                'tháng 4',
                'tháng 5',
                'tháng 6',
                'tháng 7',
                'tháng 8',
                'tháng 9',
                'tháng 10',
                'tháng 11',
                'tháng 12'
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

        // Tính số phòng còn trống
        $roomType->available_rooms = $this->calculateAvailableRooms($roomType, $checkInDate, $checkOutDate);

        // Tính giá gốc
        $roomType->total_original_price = $roomType->price * $nights * $roomCount;

        // Lấy tất cả mã giảm giá của loại phòng
        $promotions = $roomType->promotions()
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $now->toDateString())
            ->whereDate('end_date', '>=', $now->toDateString())
            ->where('quantity', '>', 0)
            ->get();

        // Chọn mã giảm giá tốt nhất (giảm nhiều nhất)
        $bestPromotion = null;
        $bestDiscountedPrice = $roomType->total_original_price;

        foreach ($promotions as $promotion) {
            $discountedPrice = $this->calculateDiscountedPrice($roomType->price, $promotion, $nights, $roomCount);
            if ($discountedPrice < $bestDiscountedPrice) {
                $bestDiscountedPrice = $discountedPrice;
                $bestPromotion = $promotion;
            }
        }

        // Gán giá giảm tốt nhất
        $roomType->total_discounted_price = $bestDiscountedPrice;

        // Tính giá mỗi đêm sau khi áp dụng mã giảm giá (chỉ để hiển thị)
        $roomType->discounted_price_per_night = $bestPromotion ? ($roomType->total_discounted_price / ($nights * $roomCount)) : $roomType->price;

        // Gán thông tin chương trình giảm giá (nếu có)
        $roomType->promotion_info = $bestPromotion ? [
            'code' => $bestPromotion->code,
            'value' => $bestPromotion->value,
            'type' => $bestPromotion->type,
        ] : null;

        // Truyền dữ liệu sang view
        return view('clients.room.detail', compact('roomType', 'checkIn', 'checkOut', 'totalGuests', 'childrenCount', 'roomCount', 'formattedDateRange', 'nights'));
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

    public function paymentsList()
    {
        $payments = Payment::where('user_id', Auth::user()->id)->get();
        return view('clients.payments', compact('payments'));
    }
}
