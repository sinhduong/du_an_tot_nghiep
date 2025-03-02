<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Room;
use App\Models\Staff;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Nhân viên';
        $staffs = Staff::orderBy('id', 'desc')->get();
        return view('admins.staffs.index', compact('staffs', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Thêm nhân viên';
        $rooms = Room::all(); // Lấy tất cả phòng
        return  view('admins.staffs.create', compact('title', 'rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStaffRequest $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'max:255'],
            'avatar'                => ['nullable', 'image', 'max:10240'],
            'birthday'              => ['nullable', 'date', 'before:today'],
            'phone'                 => [
                'required',
                'string',
                Rule::unique('staffs')
            ],
            'address'               => ['required', 'max:255'],
            'email'                 => [
                'required',
                'email',
                'max:255',
                Rule::unique('staffs')
            ],
            'status'                => [
                'required',
                Rule::in(['active', 'inactive', 'on_leave'])
            ],
            'role'                  => [
                'required',
                Rule::in(['admin', 'manager', 'employee'])
            ],
            'salary'                => ['required', 'numeric', 'min:0'],
            'date_hired'            => ['required', 'date', 'before_or_equal:today'],
            'insurance_number'      => [
                'nullable',
                'max:255',
                Rule::unique('staffs')
            ],
            'contract_type'         => ['required', 'max:255'],
            'contract_start'        => ['required', 'date', 'before_or_equal:today'],
            'contract_end'          => ['nullable', 'date', 'after_or_equal:contract_start'],
            'notes'                 => ['nullable', 'string', 'max:65535'],
            'room_ids'              => ['required', 'array'],
            'room_ids.*'            => 'exists:Rooms,id'

        ]);

        try {

            if ($request->hasFile('avatar')) {
                $data['avatar'] = Storage::put('staffs', $request->file('avatar'));
            }

            $staff = Staff::create($data);

            // Kiểm tra nếu room_ids tồn tại thì mới cập nhật manager_id
            if (isset($data['room_ids']) && count($data['room_ids']) > 0) {
                Room::whereIn('id', $data['room_ids'])->update(['manager_id' => $staff->id]);
            }

            return redirect()
                ->route('admin.staffs.index')
                ->with('success', 'Nhân viên đã được thêm thành công!');
        } catch (\Throwable $th) {

            if (!empty($data['avatar']) && Storage::exists($data['avatar'])) {
                Storage::delete($data['avatar']);
            }

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
        $rooms = Room::all();
        return view('admins.staffs.show', compact('staff', 'rooms'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        $rooms = Room::all(); // Lấy tất cả phòng
        return  view('admins.staffs.edit', compact('staff', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStaffRequest $request, Staff $staff)
    {
        $data = $request->validate([
            'name'                  => ['required', 'max:255'],
            'avatar'                => ['nullable', 'image', 'max:10240'],
            'birthday'              => ['nullable', 'date', 'before:today'],
            'phone'                 => [
                'required',
                'string',
                Rule::unique('staffs')->ignore($staff->id)
            ],
            'address'               => ['required', 'max:255'],
            'email'                 => [
                'required',
                'email',
                'max:255',
                Rule::unique('staffs')->ignore($staff->id)
            ],
            'status'                => [
                'required',
                Rule::in(['active', 'inactive', 'on_leave'])
            ],
            'role'                  => [
                'required',
                Rule::in(['admin', 'manager', 'employee'])
            ],
            'salary'                => ['required', 'numeric', 'min:0'],
            'date_hired'            => ['required', 'date', 'before_or_equal:today'],
            'insurance_number'      => [
                'nullable',
                'max:255',
                Rule::unique('staffs')->ignore($staff->id)
            ],
            'contract_type'         => ['required', 'max:255'],
            'contract_start'        => ['required', 'date', 'before_or_equal:today'],
            'contract_end'          => ['nullable', 'date', 'after_or_equal:contract_start'],
            'notes'                 => ['nullable', 'string', 'max:65535'],
            'room_ids'              => ['required', 'array'],
            'room_ids.*'            => 'exists:Rooms,id'

        ]);
        try {
            if ($request->hasFile('avatar')) {

                // dd(Storage::put('staffs', $request->file('avatar')));
                $data['avatar'] = Storage::disk('public')->put('staffs', $request->file('avatar'));

                if (
                    isset($staff->avatar) && Storage::exists($staff->avatar)
                ) {
                    Storage::delete($staff->avatar);
                }
            }
            $staff->update($data);


            // Kiểm tra nếu room_ids tồn tại thì mới cập nhật manager_id
            if (isset($data['room_ids']) && count($data['room_ids']) > 0) {
                Room::whereIn('id', $data['room_ids'])->update(['manager_id' => $staff->id]);
            }

            return back()->with('success', 'Nhân viên đã được cập nhật thành công!');
        } catch (\Throwable $th) {
            if (!empty($data['avatar']) && Storage::exists($data['avatar'])) {
                Storage::delete($data['avatar']);
            }
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
