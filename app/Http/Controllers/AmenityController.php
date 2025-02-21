<?php

namespace App\Http\Controllers;

use App\Models\amenity;
use App\Http\Requests\StoreamenityRequest;
use App\Http\Requests\UpdateamenityRequest;

class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Các Tiện Ích ';
        $room_rule = amenity::orderBy('id', 'desc')->get();
        return view('admins.amenities.index', compact('title', 'room_rule'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title= 'Thêm Tiện Ích ';
        return  view('admins.amenities.create',compact('title'));
    }

//     /**
//      * Store a newly created resource in storage.
//      */
    public function store(StoreamenityRequest $request)
{
    if ($request->isMethod('POST')) {
        // Lấy dữ liệu từ request
        $data = $request->except('_token');

        // Thêm loại phòng vào database
        amenity::create($data);
    }

    // Chuyển hướng về danh sách với thông báo thành công
    return redirect()->route('admin.amenities.index')->with('success', 'Thêm quy định phòng thành công');
}


//     /**
//      * Display the specified resource.
//      */
//     public function show(Room_type $room_type)
//     {
//         //
//     }

//     /**
//      * Show the form for editing the specified resource.
//      */
    public function edit(string $id)
    {
        $title='Sửa loại tiện ích ';
        $room_types=amenity::findOrfail($id);
        return  view('admins.amenities.edit',compact('room_types','title'));
    }

//     /**
//      * Update the specified resource in storage.
//      */
    public function update(UpdateamenityRequest $request, string $id)
    {
        // Tìm loại phòng theo ID, nếu không có sẽ báo lỗi 404
        $room_type = amenity::findOrFail($id);

        // Lấy dữ liệu từ request, loại bỏ _token và _method
        $data = $request->except('_token', '_method');

        // Cập nhật loại phòng
        $room_type->update($data);

        // Chuyển hướng về danh sách với thông báo thành công
        return redirect()->route('admin.amenities.index')->with('success', 'Cập nhật loại phòng thành công');
    }


//     /**
//      * Remove the specified resource from storage.
//      */
    public function destroy($id)
    {
        $room_type = amenity::findOrFail($id);
        $room_type->delete(); // Xóa mềm

        return redirect()->route('admin.amenities.index')->with('success', 'Loại phòng đã được xóa mềm');
    }



public function trashed()
{
    $title = 'Loại phòng đã xóa';
    $room_types = amenity::onlyTrashed()->get();
    return view('admins.amenities.trashed', compact('title', 'room_types'));
}

public function restore($id)
{
    $room_type = amenity::onlyTrashed()->findOrFail($id);
    $room_type->restore(); // Khôi phục
    return redirect()->route('admin.amenities.index')->with('success', 'Khôi phục loại phòng thành công');
}

public function forceDelete($id)
{
    $room_type = amenity::onlyTrashed()->findOrFail($id);
    $room_type->forceDelete(); // Xóa vĩnh viễn
    return redirect()->route('admin.amenities.trashed')->with('success', 'Xóa vĩnh viễn loại phòng thành công');
}
}
