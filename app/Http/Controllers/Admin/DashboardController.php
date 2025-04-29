<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
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
            $startDate = $endDate->copy()->subDays(29); // 29 vì bao gồm cả ngày hôm nay
            $dateRange = $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y');
        } else {
            // Xử lý khoảng thời gian từ input
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
            'dateRange'
        ));
    }
}
