<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use App\Http\Requests\StoreRoom_typeRequest;
use App\Http\Requests\UpdateRoom_typeRequest;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Loại phòng';
        $room_types = RoomType::orderBy('id', 'desc')->get();
        return view('admins.room-type.index', compact('title', 'room_types'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title= 'Thêm loại phòng';
        return  view('admins.room-type.create',compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoom_typeRequest $request)
{
    if ($request->isMethod('POST')) {
        // Lấy dữ liệu từ request
        $data = $request->except('_token');

        // Thêm loại phòng vào database
        RoomType::create($data);
    }

    // Chuyển hướng về danh sách với thông báo thành công
    return redirect()->route('admin.room_types.index')->with('success', 'Thêm loại phòng thành công');
}


    /**
     * Display the specified resource.
     */
    public function show(RoomType $room_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title='Sửa loại phòng';
        $room_types=RoomType::findOrfail($id);
        return  view('admins.room-type.edit',compact('room_types','title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoom_typeRequest $request, string $id)
    {
        // Tìm loại phòng theo ID, nếu không có sẽ báo lỗi 404
        $room_type = RoomType::findOrFail($id);

        // Lấy dữ liệu từ request, loại bỏ _token và _method
        $data = $request->except('_token', '_method');

        // Cập nhật loại phòng
        $room_type->update($data);

        // Chuyển hướng về danh sách với thông báo thành công
        return redirect()->route('admin.room_types.index')->with('success', 'Cập nhật loại phòng thành công');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $room_type = RoomType::findOrFail($id);
        $room_type->delete(); // Xóa mềm

        return redirect()->route('admin.room_types.index')->with('success', 'Loại phòng đã được xóa mềm');
    }



public function trashed()
{
    $title = 'Loại phòng đã xóa';
    $room_types = RoomType::onlyTrashed()->get();
    return view('admins.room-type.trashed', compact('title', 'room_types'));
}

public function restore($id)
{
    $room_type = RoomType::onlyTrashed()->findOrFail($id);
    $room_type->restore(); // Khôi phục
    return redirect()->route('admin.room_types.index')->with('success', 'Khôi phục loại phòng thành công');
}

public function forceDelete($id)
{
    $room_type = RoomType::onlyTrashed()->findOrFail($id);
    $room_type->forceDelete(); // Xóa vĩnh viễn
    return redirect()->route('admin.room_types.trashed')->with('success', 'Xóa vĩnh viễn loại phòng thành công');
}


}
