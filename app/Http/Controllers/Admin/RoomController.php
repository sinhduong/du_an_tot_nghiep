<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreroomRequest;
use App\Http\Requests\UpdateroomRequest;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Staff;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Danh sách phòng';
        $room_types_id = RoomType::all();
        $staffs = Staff::all();
        $rooms = Room::orderBy('id', 'desc')->get();
        return  view('admins.rooms.index', compact(['rooms', 'staffs', 'room_types_id'], 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Thêm phòng';
        $room_types_id = RoomType::all(); //lấy tất cả loại phòng
        $staffs_id = Staff::all(); //lấy tất cả loại phòng
        return  view('admins.rooms.create', compact(['title', 'room_types_id', 'staffs_id']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreroomRequest $request)
    {
        try {

            Room::create($request->validated());

            return redirect()
                ->route('admin.rooms.index')
                ->with('success', 'Phòng đã được thêm thành công!');
        } catch (\Throwable $th) {

            return back()
                ->with('success', true)
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(room $rooms)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $title = 'Sửa thông tin phòng';
        $room_types_id = RoomType::all(); //lấy tất cả loại phòng
        $staffs_id = Staff::all(); //lấy tất cả loại phòng
        return  view('admins.rooms.edit', compact(['title', 'room', 'room_types_id', 'staffs_id']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateroomRequest $request, Room $room)
    {
        try {
            $room->update($request->validated());
            return back()->with('success', 'Phòng đã được cập nhật thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $th->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        try {
            $room->delete();
            return redirect()
                ->route('admin.rooms.index')
                ->with('success', 'Bạn đã chuyển phòng vào thùng rác!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi xóa: ' . $e->getMessage());
        }
    }


    //  Hiển thị danh sách Phòng đã bị xóa mềm (trong thùng rác)
    public function trashed()
    {
        $room_types_id = RoomType::all();
        $staffs = Staff::all();
        $rooms = Room::onlyTrashed()->get();
        return view('admins.rooms.trashed', compact(['room_types_id', 'staffs', 'rooms']));
    }

    //  Khôi phục Phòng đã xóa mềm
    public function restore($id)
    {
        try {
            $rooms = Room::onlyTrashed()->findOrFail($id);
            $rooms->restore();

            return redirect()
                ->route('admin.rooms.index')
                ->with('success', 'Phòng đã được khôi phục!');
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Phòng không tồn tại trong thùng rác!');
        }
    }

    //  Xóa vĩnh viễn Phòng khỏi hệ thống
    public function forceDelete($id)
    {
        try {
            $rooms = Room::onlyTrashed()->findOrFail($id);
            $rooms->forceDelete();

            return redirect()
                ->route('admin.rooms.trashed')
                ->with('success', 'Phòng đã bị xóa vĩnh viễn!');
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Phòng không tồn tại trong thùng rác!');
        }
    }
}
