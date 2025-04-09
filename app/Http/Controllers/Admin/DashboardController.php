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
    public function index(Request $request)
    {
        // Các biến hiện có
        $visitorCount = User::count();
        $bookingCount = Booking::count();
        $revenueTotal = Booking::sum('total_price');

        $servicePlusRevenue = Booking::with('servicePlus')->get()->sum(function ($booking) {
            return $booking->servicePlus->sum(function ($service) {
                return $service->price * $service->pivot->quantity;
            });
        });

        $revenueTotal = $revenueTotal + $servicePlusRevenue;
        $roomsAvailable = Room::where('status', 'available')->count();
        $roomsTotal = Room::count();

        $lastMonth = Carbon::now()->subMonth();
        $visitorLastMonth = User::whereMonth('created_at', $lastMonth->month)->count();
        $bookingLastMonth = Booking::whereMonth('created_at', $lastMonth->month)->count();
        $revenueLastMonth = Booking::whereMonth('created_at', $lastMonth->month)->sum('total_price');

        $visitorGrowth = $visitorLastMonth > 0 ? (($visitorCount - $visitorLastMonth) / $visitorLastMonth * 100) : 0;
        $bookingGrowth = $bookingLastMonth > 0 ? (($bookingCount - $bookingLastMonth) / $bookingLastMonth * 100) : 0;
        $revenueGrowth = $revenueLastMonth > 0 ? (($revenueTotal - $revenueLastMonth) / $revenueLastMonth * 100) : 0;

        $miniChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $miniChartData['visitor'][] = User::whereMonth('created_at', $month->month)->count();
            $miniChartData['booking'][] = Booking::whereMonth('created_at', $month->month)->count();
            $miniChartData['revenue'][] = Booking::whereMonth('created_at', $month->month)->sum('total_price');
            $miniChartData['rooms'][] = Room::whereMonth('updated_at', $month->month)->where('status', 'available')->count();
            $miniChartData['labels'][] = $month->format('M');
        }

        $overviewData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $bookings = Booking::whereMonth('created_at', $month->month)->count();
            $revenue = Booking::whereMonth('created_at', $month->month)->sum('total_price');
            $expense = $revenue * 0.8; // Giả định expense là 80% của revenue
            $profit = $revenue - $expense;

            $overviewData['bookings'][] = $bookings;
            $overviewData['revenue'][] = $revenue;
            $overviewData['expense'][] = $expense;
            $overviewData['profit'][] = $profit;
            $overviewData['labels'][] = $month->format('M Y');
        }

        // Thêm logic để tính tổng giá theo khoảng thời gian được lọc
        $filteredTotalPrice = 0;
        $dateRange = $request->input('date_range');

        if ($dateRange) {
            // Tách khoảng thời gian từ input (định dạng: DD/MM/YYYY - DD/MM/YYYY)
            $dates = explode(' - ', $dateRange);
            if (count($dates) === 2) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();

                // Tính tổng giá trong khoảng thời gian
                $filteredTotalPrice = Booking::whereBetween('created_at', [$startDate, $endDate])
                    ->sum('total_price');
            }
        }

        return view('admins.dashboard', compact(
            'visitorCount',
            'bookingCount',
            'revenueTotal',
            'roomsAvailable',
            'roomsTotal',
            'visitorGrowth',
            'bookingGrowth',
            'revenueGrowth',
            'miniChartData',
            'overviewData',
            'filteredTotalPrice',
            'dateRange'
        ));
    }
}
