<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\ServicePlus;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:statistics')->only(['index']);
    }

    public function index(Request $request)
    {
        // Lấy khoảng thời gian lọc
        $dateRange = $request->input('date_range');
        $startDate = null;
        $endDate = null;

        if (!$dateRange) {
            $endDate = Carbon::now();
            $startDate = $endDate->copy()->subDays(29);
            $dateRange = $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y');
        } else {
            $dates = explode(' - ', $dateRange);
            if (count($dates) === 2) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();
            }
        }

        // Thống kê theo loại phòng
        $roomTypeStats = Room::select('type', \DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();

        // Thống kê dịch vụ thêm được sử dụng nhiều nhất
        $topServices = ServicePlus::withCount(['bookings' => function($query) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $query->whereBetween('bookings.created_at', [$startDate, $endDate]);
            }
        }])
        ->orderBy('bookings_count', 'desc')
        ->take(5)
        ->get();

        // Thống kê đặt phòng theo ngày trong tuần
        $bookingByDay = Booking::select(
            \DB::raw('DAYOFWEEK(created_at) as day'),
            \DB::raw('count(*) as total')
        )
        ->when($startDate && $endDate, function($query) use ($startDate, $endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->groupBy('day')
        ->get();

        // Thống kê doanh thu theo giờ trong ngày
        $revenueByHour = Booking::select(
            \DB::raw('HOUR(created_at) as hour'),
            \DB::raw('sum(total_price) as total')
        )
        ->when($startDate && $endDate, function($query) use ($startDate, $endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->groupBy('hour')
        ->get();

        // Thống kê tỷ lệ hủy đặt phòng
        $cancellationRate = Booking::when($startDate && $endDate, function($query) use ($startDate, $endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->selectRaw('
            COUNT(*) as total_bookings,
            SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_bookings
        ')
        ->first();

        $cancellationRate = $cancellationRate->total_bookings > 0 
            ? round(($cancellationRate->cancelled_bookings / $cancellationRate->total_bookings) * 100, 2)
            : 0;

        return view('admins.statistics', compact(
            'roomTypeStats',
            'topServices',
            'bookingByDay',
            'revenueByHour',
            'cancellationRate',
            'dateRange'
        ));
    }
} 