<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\ServicePlus;
use App\Models\Review;
use App\Models\Guest;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseAdminController
{
    public function __construct(){
        $this->middleware('permission:dashboard')->only(['index']);
    }

    public function index(Request $request)
    {
        // Lấy khoảng thời gian lọc
        $dateRange = $request->input('date_range');
        $startDate = null;
        $endDate = null;

        // Nếu không có dateRange, mặc định là 30 ngày gần nhất
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

        // Query builder cơ bản cho đặt phòng
        $bookingQuery = Booking::query();
        if ($startDate && $endDate) {
            $bookingQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Tính toán các chỉ số chính
        $bookingCount = $bookingQuery->count();
        $revenueTotal = $bookingQuery->sum('total_price');

        // Tính doanh thu từ dịch vụ thêm
        $servicePlusRevenue = $bookingQuery->with('servicePlus')->get()->sum(function ($booking) {
            return $booking->servicePlus->sum(function ($service) {
                return $service->price * $service->pivot->quantity;
            });
        });
        $revenueTotal += $servicePlusRevenue;

        // Tính chi phí (80% của doanh thu)
        $expenseTotal = $revenueTotal * 0.8;
        $profitTotal = $revenueTotal - $expenseTotal;

        // Thống kê theo loại phòng
        $roomTypeStats = Room::join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select('room_types.name as type', DB::raw('count(*) as total'))
            ->groupBy('room_types.name')
            ->get();

        // Top dịch vụ được sử dụng
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
            DB::raw('DAYOFWEEK(created_at) as day'),
            DB::raw('count(*) as total')
        )
        ->when($startDate && $endDate, function($query) use ($startDate, $endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->groupBy('day')
        ->orderBy('day')
        ->get();

        // Thống kê doanh thu theo giờ
        $revenueByHour = Booking::select(
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('sum(total_price) as total')
        )
        ->when($startDate && $endDate, function($query) use ($startDate, $endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->groupBy('hour')
        ->orderBy('hour')
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

        // Thống kê khách hàng
        $customerStats = [
            'total' => User::count(),
            'new' => User::when($startDate && $endDate, function($query) use ($startDate, $endDate) {
                    return $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->count(),
            'loyal' => Booking::select('user_id', DB::raw('count(*) as booking_count'))
                ->groupBy('user_id')
                ->having('booking_count', '>', 3)
                ->count()
        ];

        // Thống kê đánh giá
        $reviewStats = Review::when($startDate && $endDate, function($query) use ($startDate, $endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->selectRaw('
            COUNT(*) as total_reviews,
            AVG(rating) as average_rating,
            SUM(CASE WHEN rating >= 4 THEN 1 ELSE 0 END) as positive_reviews
        ')
        ->first();

        // Dữ liệu cho biểu đồ mini (6 tháng gần nhất)
        $miniChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthQuery = Booking::whereMonth('created_at', $month->month);

            if ($startDate && $endDate) {
                $monthQuery->whereBetween('created_at', [
                    max($startDate, $month->copy()->startOfMonth()),
                    min($endDate, $month->copy()->endOfMonth())
                ]);
            }

            $monthBookings = $monthQuery->count();
            $monthRevenue = $monthQuery->sum('total_price');
            $monthServiceRevenue = $monthQuery->with('servicePlus')->get()->sum(function ($booking) {
                return $booking->servicePlus->sum(function ($service) {
                    return $service->price * $service->pivot->quantity;
                });
            });
            $monthRevenue += $monthServiceRevenue;

            $miniChartData['booking'][] = $monthBookings;
            $miniChartData['revenue'][] = $monthRevenue;
            $miniChartData['rooms'][] = Room::whereMonth('updated_at', $month->month)
                ->where('status', 'available')
                ->count();
            $miniChartData['labels'][] = $month->format('M');
        }

        // Dữ liệu cho biểu đồ tổng quan (12 tháng gần nhất)
        $overviewData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthQuery = Booking::whereMonth('created_at', $month->month);

            if ($startDate && $endDate) {
                $monthQuery->whereBetween('created_at', [
                    max($startDate, $month->copy()->startOfMonth()),
                    min($endDate, $month->copy()->endOfMonth())
                ]);
            }

            $monthBookings = $monthQuery->count();
            $monthRevenue = $monthQuery->sum('total_price');
            $monthServiceRevenue = $monthQuery->with('servicePlus')->get()->sum(function ($booking) {
                return $booking->servicePlus->sum(function ($service) {
                    return $service->price * $service->pivot->quantity;
                });
            });
            $monthRevenue += $monthServiceRevenue;
            $monthExpense = $monthRevenue * 0.8;
            $monthProfit = $monthRevenue - $monthExpense;

            $overviewData['bookings'][] = $monthBookings;
            $overviewData['revenue'][] = $monthRevenue;
            $overviewData['expense'][] = $monthExpense;
            $overviewData['profit'][] = $monthProfit;
            $overviewData['labels'][] = $month->format('M Y');
        }

        // Các chỉ số khác
        $roomsAvailable = Room::where('status', 'available')->count();
        $roomsTotal = Room::count();

        return view('admins.dashboard', compact(
            'bookingCount',
            'revenueTotal',
            'expenseTotal',
            'profitTotal',
            'roomsAvailable',
            'roomsTotal',
            'miniChartData',
            'overviewData',
            'dateRange',
            'roomTypeStats',
            'topServices',
            'bookingByDay',
            'revenueByHour',
            'cancellationRate',
            'customerStats',
            'reviewStats',
        ));
    }
}
