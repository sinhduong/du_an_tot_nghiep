<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Room;
use App\Models\Staff;
use App\Models\StaffRole;
use App\Models\StaffShift;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        $roles = StaffRole::all();
        $shifts = StaffShift::all();
        return view('admins.staffs.create', compact(['rooms', 'roles', 'shifts', 'users']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStaffRequest $request)
    {
        $data = $request->validate([
            'user_id'               => 'required|exists:users,id',
            'role_id'               => 'required|exists:staff_roles,id',
            'shift_id'              => 'nullable|exists:staff_shifts,id',
            'notes'                 => ['nullable', 'string', 'max:65535'],
            'room_ids'              => ['nullable', 'array'],
            'room_ids.*'            => 'exists:Rooms,id|nullable'
        ]);

        try {

            // if ($request->hasFile('avatar')) {
            //     $data['avatar'] = Storage::put('staffs', $request->file('avatar'));
            // }

            $staff = Staff::create($data);

            // Kiểm tra nếu room_ids tồn tại thì mới cập nhật manager_id
            if (isset($data['room_ids']) && count($data['room_ids']) > 0) {
                Room::whereIn('id', $data['room_ids'])->update(['manager_id' => $staff->id]);
            }

            return redirect()
                ->route('admin.staffs.index')
                ->with('success', 'Nhân viên đã được thêm thành công!');
        } catch (\Throwable $th) {

            // if (!empty($data['avatar']) && Storage::exists($data['avatar'])) {
            //     Storage::delete($data['avatar']);
            // }

            return back()
                ->with('success', true)
                ->with('error', $th->getMessage());
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
        $users  = User::whereNotIn('id', Staff::where('id', '!=', $staff->id)->pluck('user_id'))
            ->orWhere('id', $staff->user_id)
            ->get();
        $rooms  = Room::all();
        $roles  = StaffRole::all();
        $shifts = StaffShift::all();

        return view('admins.staffs.edit', compact('staff', 'rooms', 'roles', 'shifts', 'users'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStaffRequest $request, Staff $staff)
    {
        $data = $request->validate([
            'user_id'               => 'required|exists:users,id',
            'role_id'               => 'required|exists:staff_roles,id',
            'shift_id'              => 'nullable|exists:staff_shifts,id',
            'notes'                 => ['nullable', 'string', 'max:65535'],
            'room_ids'              => ['nullable', 'array'],
            'room_ids.*'            => 'exists:Rooms,id|nullable'
        ]);
        try {
            // if ($request->hasFile('avatar')) {

            //     $data['avatar'] = Storage::disk('public')->put('staffs', $request->file('avatar'));

            //     if (
            //         isset($staff->avatar) && Storage::exists($staff->avatar)
            //     ) {
            //         Storage::delete($staff->avatar);
            //     }
            // }
            $staff->update($data);


            // Kiểm tra nếu room_ids tồn tại thì mới cập nhật manager_id
            if (isset($data['room_ids']) && count($data['room_ids']) > 0) {
                Room::whereIn('id', $data['room_ids'])->update(['manager_id' => $staff->id]);
            }

            return back()->with('success', 'Nhân viên đã được cập nhật thành công!');
        } catch (\Throwable $th) {
            // if (!empty($data['avatar']) && Storage::exists($data['avatar'])) {
            //     Storage::delete($data['avatar']);
            // }
            return back()
                ->with('success', false)
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        try {
            $staff->delete();

            return redirect()
                ->route('admin.staffs.index')
                ->with('success', 'Bạn đã chuyển nhân viên vào thùng rác!');
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
