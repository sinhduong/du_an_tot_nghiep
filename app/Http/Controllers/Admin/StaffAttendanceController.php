<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAttendanceController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = StaffAttendance::with('staff')->where('staff_id', Auth::id());
        return view('admins.staff_attendances.index', compact('attendances'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function checkIn()
    {
        $today = Carbon::today()->toDateString();

        // Kiểm tra đã check-in chưa
        $attendance = StaffAttendance::where('staff_id', Auth::id())->where('date', $today)->first();
        if ($attendance) {
            return back()->with('error', 'Bạn đã check-in hôm nay rồi!');
        }

        StaffAttendance::create([
            'staff_id' => Auth::id(),
            'check_in' => Carbon::now(),
            'date' => $today,
        ]);

        return back()->with('success', 'Check-in thành công!');
    }

    public function checkOut()
    {
        $today = Carbon::today()->toDateString();
        $attendance = StaffAttendance::where('staff_id', Auth::id())->where('date', $today)->first();

        if (!$attendance || $attendance->check_out) {
            return back()->with('error', 'Bạn chưa check-in hoặc đã check-out!');
        }

        $attendance->update(['check_out' => Carbon::now()]);

        return back()->with('success', 'Check-out thành công!');
    }
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
