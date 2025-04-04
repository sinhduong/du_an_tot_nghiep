<?php

namespace App\Http\Controllers\Client;
use App\Mail\ContactEmail;
use App\Models\Amenity;
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
use App\Models\SaleRoomType;
use Illuminate\Http\Request;
use App\Helpers\FormatHelper;
use App\Http\Controllers\Controller;
use App\Models\RulesAndRegulation;
use App\Models\System;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactEmtail;

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
            if ($nights < 1)
                $nights = 1;

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

        return view('clients.home', compact('roomTypes', 'checkIn', 'checkOut', 'totalGuests', 'childrenCount', 'roomCount', 'formattedDateRange', 'nights', 'promotions','systems'));
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
            if ($nights < 1)
                $nights = 1;

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

        \Log::info("Room: {$roomType->name}, Price: {$roomType->price}, Nights: $nights, RoomCount: $roomCount, Original: {$roomType->total_original_price}, Discounted: {$roomType->total_discounted_price}, SaleRoomType: " . json_encode($bestSaleRoomType ? $bestSaleRoomType->toArray() : null));

        return view('clients.room.detail', compact('roomType', 'checkIn', 'checkOut', 'totalGuests', 'childrenCount', 'roomCount', 'formattedDateRange', 'nights', 'systems', 'room_rule', 'amenities'));
    }

    public function faqs()
    {
        $systems = System::orderBy('id', 'desc')->first();
        $faqs = Faq::where('is_active', 1)->get();
        return view('clients.faq', compact('faqs', 'systems'));
    }

    public function services()
    {
        $systems = System::orderBy('id', 'desc')->first();
        $services = Service::where('is_active', 1)->get();
        return view('clients.service', compact('services', 'systems'));
    }

    public function abouts()
    {
        $systems = System::orderBy('id', 'desc')->first();
        $about = About::where('is_use', 1)->first() ?? new About(['about' => 'Chưa có nội dung nào được thiết lập.']);
        return view('clients.about', compact('about', 'systems'));
    }
    public function send(Request $request)
    {
        // Validate dữ liệu từ form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Dữ liệu từ form
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        // Gửi email từ email của khách hàng đến hainamkid@gmail.com
        Mail::to('hainamkid@gmail.com') // Địa chỉ nhận là email khách sạn
            ->send(new ContactEmail($data, $request->email));

        return redirect()->back()->with('success', 'Your message has been sent successfully!');
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
