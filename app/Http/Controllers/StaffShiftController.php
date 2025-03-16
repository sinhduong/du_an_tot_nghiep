<?php
namespace App\Http\Controllers;

use App\Models\Admin\Staff;
use App\Models\Admin\StaffShift;
use Illuminate\Http\Request;

class StaffShiftController extends Controller
{
    public function index()
    {
        $staff_shifts = StaffShift::with('staff')->get();
        return view('admins.staff_shifts.index', compact('staff_shifts'));
    }

    public function create()
    {
        $staffs = Staff::all();
        return view('admins.staff_shifts.create', compact('staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        StaffShift::create($request->all());
        return redirect()->route('admin.staff_shifts.index')->with('success', 'Ca làm việc được tạo thành công.');
    }

    public function edit(StaffShift $staffShift)
    {
        $staffs = Staff::all();
        return view('admins.staff_shifts.edit', compact('staffShift', 'staffs'));
    }

    public function update(Request $request, StaffShift $staffShift)
    {
        $request->validate([
            'name' => 'nullable',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
        ]);
        $staffShift->update($request->all());
        return redirect()->route('admin.staff_shifts.index')->with('success', 'Cập nhật ca làm việc thành công.');
    }

    public function destroy(StaffShift $staffShift)
    {
        $staffShift->delete();
        return redirect()->route('admin.staff_shifts.index')->with('success', 'Xóa ca làm việc thành công.');
    }
}
