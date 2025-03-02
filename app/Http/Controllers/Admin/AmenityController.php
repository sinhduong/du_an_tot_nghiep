<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Amenity;
use App\Http\Requests\StoreAmenityRequest;
use App\Http\Requests\UpdateAmenityRequest;
use App\Models\RoomType;

class AmenityController extends Controller
{

    public function index()
    {
        $title = 'Danh Sách Tiện nghi';
        $amenities = Amenity::with('roomTypes')->orderBy('id', 'desc')->get();
        return view('admins.amenities.index', compact('title', 'amenities'));
    }

    public function create()
    {
        $title = 'Thêm Tiện nghi Mới';
        $roomTypes = RoomType::pluck('name', 'id')->all(); // Lấy danh sách loại phòng
        return view('admins.amenities.create', compact('title', 'roomTypes'));
    }


    public function store(StoreAmenityRequest $request)
    {
        if ($request->isMethod('POST')) {
            // Tạo tiện nghi mới
            $data = $request->except('_token', 'room_type_id');
            $amenity = Amenity::create($data);

            // Gán loại phòng vào tiện nghi
            $roomTypeIds = $request->input('room_type_id', []);
            if (!empty($roomTypeIds)) {
                $amenity->roomTypes()->attach($roomTypeIds);
            }

            return redirect()->route('admin.amenities.index')->with('success', 'Thêm tiện nghi thành công');
        }
    }


    public function edit(string $id)
    {
        $title = 'Sửa Tiện nghi';
        $amenity = Amenity::with('roomTypes')->findOrFail($id);
        $roomTypes = RoomType::pluck('name', 'id')->all(); // Lấy danh sách loại phòng
        return view('admins.amenities.edit', compact('amenity', 'title', 'roomTypes'));
    }

    public function update(UpdateAmenityRequest $request, string $id)
    {
        $amenity = Amenity::findOrFail($id);
        $data = $request->except('_token', '_method', 'room_type_id');
        $amenity->update($data);

        // Cập nhật loại phòng cho tiện nghi
        $roomTypeIds = $request->input('room_type_id', []);
        $amenity->roomTypes()->sync($roomTypeIds);

        return redirect()->route('admin.amenities.index')->with('success', 'Cập nhật tiện nghi thành công');
    }


    public function destroy($id)
    {
        $amenity = Amenity::findOrFail($id);
        $amenity->delete();
        return redirect()->route('admin.amenities.index')->with('success', 'Tiện nghi đã được xóa mềm');
    }

    public function trashed()
    {
        $title = 'Danh Sách Tiện nghi Đã Xóa';
        $amenities = Amenity::onlyTrashed()->get();
        return view('admins.amenities.trashed', compact('title', 'amenities'));
    }

    public function restore($id)
    {
        $amenity = Amenity::onlyTrashed()->findOrFail($id);
        $amenity->restore();
        return redirect()->route('admin.amenities.index')->with('success', 'Khôi phục tiện nghi thành công');
    }


    public function forceDelete($id)
    {
        $amenity = Amenity::onlyTrashed()->findOrFail($id);
        $amenity->forceDelete();
        return redirect()->route('admin.amenities.trashed')->with('success', 'Xóa vĩnh viễn tiện nghi thành công');
    }
}
