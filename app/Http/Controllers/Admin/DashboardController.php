<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Booking;
use App\Models\Admin\Room;
use App\Models\Admin\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $visitorCount = User::count();
        $bookingCount = Booking::count();
        $revenueTotal = Booking::sum('total_price');
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

        return view('admins.dashboard', compact(
            'visitorCount', 'bookingCount', 'revenueTotal', 'roomsAvailable', 'roomsTotal',
            'visitorGrowth', 'bookingGrowth', 'revenueGrowth',
            'miniChartData', 'overviewData'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
