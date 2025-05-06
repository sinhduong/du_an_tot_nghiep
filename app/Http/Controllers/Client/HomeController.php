<?php

namespace App\Http\Controllers\Client;

use Log;
use Carbon\Carbon;
use App\Models\Faq;
use App\Models\About;
use App\Models\System;
use App\Models\Amenity;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Contacts;
use App\Models\RoomType;
use App\Models\Promotion;
use App\Mail\ContactEmail;
use App\Mail\ContactEmtail;
use App\Models\Introduction;
use App\Models\SaleRoomType;
use Illuminate\Http\Request;
use App\Helpers\FormatHelper;
use App\Models\RulesAndRegulation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Review;

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
        Log::info("Total rooms for {$roomType->name}: {$totalRooms}");

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
                    ->whereNotIn('status', ['cancelled', 'cancelled_without_refund', 'refunded', 'check_out']);
            })
            ->sum('room_quantity');
        // dd($bookedRooms);

        Log::info("Booked rooms for {$roomType->name}: {$bookedRooms}, Check-in: {$checkInDate}, Check-out: {$checkOutDate}");

        return max(0, $totalRooms - $bookedRooms);
    }

    public function index(Request $request)
    {
        Carbon::setLocale('vi');
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        // Giá trị mặc định cho check_in và check_out
        $checkIn = $request->input('check_in', Carbon::today()->setHour(14)->setMinute(0)->setSecond(0)->toDateTimeString());
        $checkOut = $request->input('check_out', Carbon::tomorrow()->setHour(12)->setMinute(0)->setSecond(0)->toDateTimeString());
        $totalGuests = (int) $request->input('total_guests', 2);
        $childrenCount = (int) $request->input('children_count', 0);
        $roomCount = (int) $request->input('room_count', 1);
        // dd( $checkOut );
        try {
            $checkInDate = Carbon::parse($checkIn);
            $checkOutDate = Carbon::parse($checkOut);
            $now = Carbon::now();

            // Kiểm tra nếu ngày nhận phòng là ngày hiện tại
//            if ($checkInDate->isToday()) {
//                // Nếu thời gian hiện tại từ 21:00 đến 23:59:59, đẩy ngày nhận phòng sang ngày hôm sau
//                if ($now->hour >= 21 && $now->hour < 24) {
//                    $checkInDate = $now->copy()->addDay()->setHour(14)->setMinute(0)->setSecond(0);
//                    $checkIn = $checkInDate->toDateTimeString();
//                }
//            }


            // Đảm bảo ngày trả phòng luôn sau ngày nhận phòng
            if ($checkInDate->gte($checkOutDate)) {
                $checkOutDate = $checkInDate->copy()->addDay()->setHour(12)->setMinute(0)->setSecond(0);
                $checkOut = $checkOutDate->toDateTimeString();
                // $request->session()->flash('info', 'Ngày trả phòng đã được điều chỉnh để sau ngày nhận phòng.');
            } else {
                // Giữ nguyên ngày trả phòng nếu nó đã hợp lệ (sau ngày nhận phòng)
                $checkOut = $checkOutDate->toDateTimeString();
            }

            $nights = $checkInDate->diffInDays($checkOutDate);
            if ($nights < 1) {
                $nights = 1;
            }

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
            ->with(['roomTypeImages', 'amenities', 'rooms', 'saleRoomTypes'])
            ->where('is_active', true)
            ->where('max_capacity', '>=', $totalPeople)
            ->where('children_free_limit', '>=', $childrenCount)
            ->get();

        $roomTypes = $roomTypes->filter(function ($roomType) use ($checkInDate, $checkOutDate, $roomCount, $nights, $now) {
            $availableRooms = $this->calculateAvailableRooms($roomType, $checkInDate, $checkOutDate);
            $roomType->available_rooms = $availableRooms;

            $roomType->total_original_price = $roomType->price * $nights * $roomCount;

            $saleRoomTypes = $roomType->saleRoomTypes()
                ->where('status', 'active')
                ->whereDate('start_date', '<=', $now->toDateString())
                ->whereDate('end_date', '>=', $now->toDateString())
                ->get();

            $bestSaleRoomType = null;
            $bestDiscountedPrice = $roomType->total_original_price;

            foreach ($saleRoomTypes as $saleRoomType) {
                $discountedPrice = $this->calculateDiscountedPrice($roomType->price, $saleRoomType, $nights, $roomCount);
                if ($discountedPrice < $bestDiscountedPrice) {
                    $bestDiscountedPrice = $discountedPrice;
                    $bestSaleRoomType = $saleRoomType;
                }
            }

            $roomType->total_discounted_price = $bestDiscountedPrice;
            $roomType->discounted_price_per_night = $bestSaleRoomType ? ($roomType->total_discounted_price / ($nights * $roomCount)) : $roomType->price;
            $roomType->promotion_info = $bestSaleRoomType ? [
                'name' => $bestSaleRoomType->name,
                'value' => $bestSaleRoomType->value,
                'type' => $bestSaleRoomType->type,
            ] : null;

            return $availableRooms >= $roomCount;
        })->sortBy([['total_discounted_price', 'asc'], ['available_rooms', 'desc']])->values();
        $systems = System::orderBy('id', 'desc')->first();

        $promotions = Promotion::where('status', 'active')
            ->where('type', 'percent')
            ->where('end_date', '>=', now())
            ->get();

        // Thêm code mới để tính toán giới hạn
        $maxCapacity = $roomTypes->max('max_capacity') ?? 4;
        $maxChildrenLimit = $roomTypes->max('children_free_limit') ?? 2;
        $totalAvailableRooms = 0;
        foreach ($roomTypes as $roomType) {
            $availableRooms = $this->calculateAvailableRooms($roomType, $checkInDate, $checkOutDate);
            $totalAvailableRooms += $availableRooms;
        }

        $reviews = Review::with(['user', 'booking'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('clients.home', compact(
            'roomTypes',
            'checkIn',
            'checkOut',
            'totalGuests',
            'childrenCount',
            'roomCount',
            'formattedDateRange',
            'nights',
            'promotions',
            'systems',
            'maxCapacity',
            'maxChildrenLimit',
            'totalAvailableRooms',
            'reviews'
        ));
    }

    private function calculateDiscountedPrice($originalPrice, $saleRoomType, $nights, $roomCount)
    {
        $totalPrice = $originalPrice * $nights * $roomCount;
        if (!$saleRoomType || $saleRoomType->status !== 'active') {
            return $totalPrice;
        }

        $discount = $saleRoomType->type === 'percent'
            ? $totalPrice * ($saleRoomType->value / 100)
            : $saleRoomType->value * $roomCount;

        return max(0, $totalPrice - $discount);
    }

    public function show(Request $request, $id)
    {
        Carbon::setLocale('vi');
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $checkIn = $request->input('check_in', Carbon::today()->setHour(14)->setMinute(0)->setSecond(0)->toDateTimeString());
        $checkOut = $request->input('check_out', Carbon::tomorrow()->setHour(12)->setMinute(0)->setSecond(0)->toDateTimeString());
        $totalGuests = (int) $request->input('total_guests', 2);
        $childrenCount = (int) $request->input('children_count', 0);
        $roomCount = (int) $request->input('room_count', 1);
        $systems = System::orderBy('id', 'desc')->first();
        $room_rule = RulesAndRegulation::orderBy('id', 'desc')->get();
        $amenities = Amenity::with('roomTypes')->orderBy('id', 'desc')->get();

        try {
            $checkInDate = Carbon::parse($checkIn);
            $checkOutDate = Carbon::parse($checkOut);
            $now = Carbon::now();

            // Kiểm tra nếu ngày nhận phòng là ngày hiện tại
//            if ($checkInDate->isToday()) {
//                // Nếu thời gian hiện tại từ 21:00 đến 23:59:59, đẩy ngày nhận phòng sang ngày hôm sau
//                if ($now->hour >= 21 && $now->hour < 24) {
//                    $checkInDate = $now->copy()->addDay()->setHour(14)->setMinute(0)->setSecond(0);
//                    $checkIn = $checkInDate->toDateTimeString();
//                }
//            }

            // Đảm bảo ngày trả phòng luôn sau ngày nhận phòng
            if ($checkInDate->gte($checkOutDate)) {
                $checkOutDate = $checkInDate->copy()->addDay()->setHour(12)->setMinute(0)->setSecond(0);
                $checkOut = $checkOutDate->toDateTimeString();
                // $request->session()->flash('info', 'Ngày trả phòng đã được điều chỉnh để sau ngày nhận phòng.');
            } else {
                // Giữ nguyên ngày trả phòng nếu nó đã hợp lệ (sau ngày nhận phòng)
                $checkOut = $checkOutDate->toDateTimeString();
            }

            $nights = $checkInDate->diffInDays($checkOutDate);
            if ($nights < 1) {
                $nights = 1;
            }

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

        $roomType = RoomType::with(['roomTypeImages', 'amenities', 'rooms', 'saleRoomTypes', 'services', 'rulesAndRegulations'])
            ->where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        $availableRooms = $this->calculateAvailableRooms($roomType, $checkInDate, $checkOutDate);
        $roomType->available_rooms = $availableRooms;

        $roomType->total_original_price = $roomType->price * $nights * $roomCount;

        $saleRoomTypes = $roomType->saleRoomTypes()
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $now->toDateString())
            ->whereDate('end_date', '>=', $now->toDateString())
            ->get();

        $bestSaleRoomType = null;
        $bestDiscountedPrice = $roomType->total_original_price;

        foreach ($saleRoomTypes as $saleRoomType) {
            $discountedPrice = $this->calculateDiscountedPrice($roomType->price, $saleRoomType, $nights, $roomCount);
            if ($discountedPrice < $bestDiscountedPrice) {
                $bestDiscountedPrice = $discountedPrice;
                $bestSaleRoomType = $saleRoomType;
            }
        }

        $roomType->total_discounted_price = $bestDiscountedPrice;
        $roomType->discounted_price_per_night = $bestSaleRoomType ? ($roomType->total_discounted_price / ($nights * $roomCount)) : $roomType->price;
        $roomType->promotion_info = $bestSaleRoomType ? [
            'name' => $bestSaleRoomType->name,
            'value' => $bestSaleRoomType->value,
            'type' => $bestSaleRoomType->type,
        ] : null;

        Log::info("Room: {$roomType->name}, Price: {$roomType->price}, Nights: $nights, RoomCount: $roomCount, Original: {$roomType->total_original_price}, Discounted: {$roomType->total_discounted_price}, SaleRoomType: " . json_encode($bestSaleRoomType ? $bestSaleRoomType->toArray() : null));

        return view('clients.room.detail', compact('roomType', 'checkIn', 'checkOut', 'totalGuests', 'childrenCount', 'roomCount', 'formattedDateRange', 'nights', 'systems', 'room_rule', 'amenities'));
    }
    public function faqs()
    {
        $faqs = Faq::where('is_active', 1)->get();
        return view('clients.faq', compact('faqs'));
    }

    public function services()
    {
        $systems = System::orderBy('id', 'desc')->first();
        $services = Service::where('is_active', 1)->get();
        return view('clients.service', compact('services', 'systems'));
    }

    public function policies()
    {
        $policies = Policy::where('is_use', 1)->get();
        return view('clients.policies', compact('policies'));
    }

    public function contacts()
    {
        $contact = Contacts::orderBy('id', 'desc')->first(); // Có thể bỏ nếu không dùng
        $system = System::orderBy('id', 'desc')->first(); // Lấy thông tin từ admin
        return view('clients.contact', compact('contact', 'system'));
    }

    public function send(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'subject' => 'required|string|max:255',
                'message' => 'required|string',
                'phone' => 'nullable|string|max:20',
            ]);

            $data = [
                'title' => $request->subject,
                'phone' => $request->phone ?? '',
                'email' => $request->email,
                'content' => $request->message,
                'status' => 'pending',
            ];

            Contacts::create($data);

            return redirect()->back()->with('success', 'Bạn đã liên hệ, đợi chúng tôi trả lời nhe iuuu !');
        } catch (\Exception $e) {
            Log::error('Lỗi: ' . $e->getMessage());
            dd($e->getMessage()); // Xem lỗi cụ thể
            return redirect()->back()->with('error', 'Có lỗi xảy ra!');
        }
    }

    public function introductions()
    {
        $systems = System::orderBy('id', 'desc')->first();
        $introduction = Introduction::where('is_use', 1)->first() ?? new Introduction(['introduction' => 'Chưa có nội dung nào được thiết lập.']);
        return view('clients.introduction', compact('introduction', 'systems'));
    }

    public function paymentsList()
    {
        $payments = Payment::where('user_id', Auth::user()->id)->get();
        return view('clients.payments', compact('payments'));
    }

    public function header()
    {
        $systems = System::where('is_use', 1)->get();
        return view('clients.header', compact('systems'));
    }

    public function room_view()
    {
        $roomTypes = RoomType::with(['roomTypeImages', 'amenities'])
            ->where('is_active', true)
            ->get();
        $systems = System::orderBy('id', 'desc')->first();
        return view('clients.room.room', compact('roomTypes', 'systems'));
    }
}
