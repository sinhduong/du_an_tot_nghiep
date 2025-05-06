<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffFormRequest;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Room;
use App\Models\Staff;
use App\Models\StaffRole;
use App\Models\StaffShift;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class StaffController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:staffs_list')->only(['index']);
        $this->middleware('permission:staffs_create')->only(['create', 'store']);
        $this->middleware('permission:staffs_detail')->only(['show']);
        $this->middleware('permission:staffs_update')->only(['edit', 'update']);
        $this->middleware('permission:staffs_delete')->only(['destroy']);
        $this->middleware('permission:staffs_trashed')->only(['trashed']);
        $this->middleware('permission:staffs_restore')->only(['restore']);
        $this->middleware('permission:staffs_force_delete')->only(['forceDelete']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Nhân viên';
        $staffs = Staff::with('role', 'shift', 'user')->get();
        return view('admins.staffs.index', compact('staffs', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Thêm nhân viên';
        $users = User::whereNotIn('id', Staff::pluck('user_id'))->get();
        $rooms = Room::all(); // Lấy tất cả phòng
        $roles = Role::all();
        $shifts = StaffShift::all();
        return view('admins.staffs.create', compact(['rooms', 'roles', 'shifts', 'users']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StaffFormRequest $request)
    {
        try {
            $data = $request->all();
            DB::beginTransaction();
            $user = User::create($data);
            $data['user_id'] = $user->id;
            $staff = Staff::create($data);
            $roleName = Role::findOrFail($data['role_id'])->name;
            $user->assignRole($roleName);
            DB::commit();
            return redirect()->route('admin.staffs.index')->with('success', 'Nhân viên đã được thêm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        $users  = User::all();
        $rooms  = $staff->rooms;
        $roles  = StaffRole::all();
        $shifts = $staff->shift;

        return view('admins.staffs.show', compact(['staff', 'users', 'rooms', 'roles', 'shifts']));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        $staff = Staff::find($staff->id);
        $roles  = Role::all();

        return view('admins.staffs.edit', compact('staff', 'roles'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(StaffFormRequest $request, $id)
    {
        try {
            $data = $request->all();
            DB::beginTransaction();
            $staff = Staff::find($id);
            $user = $staff->user;
            if ($data['password']) {
                $user->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                ]);
            } else {
                $user->update([
                    'name' => $data['name'],
                    'email' => $data['email']
                ]);
            }
            $staff->update([
                'role_id' => $data['role_id'],
                'status' => $data['status'],
                'notes' => $data['notes'],
            ]);
            $roleName = Role::findOrFail($data['role_id'])->name;
            $user->syncRoles([$roleName]);

            DB::commit();
            return redirect()->route('admin.staffs.index')->with('success', 'Nhân viên đã được cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $staff = Staff::find($id);
            $staff->delete();
            $staff->user->delete();
            return redirect()
                ->route('admin.staffs.index')
                ->with('success', 'Nhân viên đã được xóa thành công !');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi xóa: ' . $e->getMessage());
        }
    }

    //  Hiển thị danh sách nhân viên đã bị xóa mềm (trong thùng rác)
    public function trashed()
    {
        $staffs = Staff::onlyTrashed()->get();
        return view('admins.staffs.trashed', compact('staffs'));
    }

    //  Khôi phục nhân viên đã xóa mềm
    public function restore($id)
    {
        try {
            $staff = Staff::onlyTrashed()->findOrFail($id);
            $staff->restore();

            return redirect()
                ->route('admin.staffs.index')
                ->with('success', 'Nhân viên đã được khôi phục!');
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Nhân viên không tồn tại trong thùng rác!');
        }
    }

    //  Xóa vĩnh viễn nhân viên khỏi hệ thống
    public function forceDelete($id)
    {
        try {
            $staff = Staff::onlyTrashed()->findOrFail($id);
            $staff->forceDelete();

            return redirect()
                ->route('admin.staffs.trashed')
                ->with('success', 'Nhân viên đã bị xóa vĩnh viễn!');
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Nhân viên không tồn tại trong thùng rác!');
        }
    }
}
