<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffRole;
use Illuminate\Http\Request;

class StaffRoleController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:staff_roles_list')->only(['index']);
        $this->middleware('permission:staff_roles_create')->only(['create', 'store']);
        $this->middleware('permission:staff_roles_update')->only(['edit', 'update']);
        $this->middleware('permission:staff_roles_delete')->only(['destroy']);
    }
    public function index()
    {
        $roles = StaffRole::all();
        return view('admins.staff_roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admins.staff_roles.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:staff_roles']);
        StaffRole::create($request->all());
        return redirect()->route('admin.staff_roles.index')->with('success', 'Vai trò được tạo thành công.');
    }

    public function edit(StaffRole $staffRole)
    {
        return view('admins.staff_roles.edit', compact('staffRole'));
    }

    public function update(Request $request, StaffRole $staffRole)
    {
        $request->validate(['name' => 'required|unique:staff_roles,name,'.$staffRole->id]);
        $staffRole->update($request->all());
        return redirect()->route('admin.staff_roles.index')->with('success', 'Cập nhật vai trò thành công.');
    }

    public function destroy(StaffRole $staffRole)
    {
        $staffRole->delete();
        return redirect()->route('admin.staff_roles.index')->with('success', 'Xóa vai trò thành công.');
    }
}
