<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAmenityRequest;
use App\Http\Requests\UpdateAmenityRequest;
use App\Models\Admin\Amenity;
use App\Models\Admin\RoomType;
use Illuminate\Support\Facades\DB;

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
        $roomTypes = RoomType::all();
        return view('admins.amenities.create', compact('title', 'roomTypes'));
    }


    public function store(StoreAmenityRequest $request)
    {

        try {
            DB::beginTransaction();
            $service = Amenity::create($request->all());
            if ($request->has('roomTypes')) {
                $service->roomTypes()->sync($request->roomTypes);
            }
            DB::commit();
            return redirect()->route('admin.amenities.index')->with('success', 'Thêm dịch vụ thành công');
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }


    public function edit(string $id)
    {
        $title = 'Sửa Tiện nghi';
        $amenity = Amenity::findOrFail($id);
        $roomTypes = RoomType::all();
        $selectedRoomTypes = $amenity->roomTypes->pluck('id')->toArray();

        return view('admins.amenities.edit', compact('amenity', 'title', 'roomTypes','selectedRoomTypes'));

    }

    public function update(UpdateAmenityRequest $request, string $id)
    {

        try {
            DB::beginTransaction();
            $service = Amenity::findOrFail($id);

            $service->update($request->all());

            if ($request->has('roomTypes')) {
                $service->roomTypes()->sync($request->roomTypes);
            }
            DB::commit();
            return redirect()->route('admin.amenities.index')->with('success', 'Cập nhật dịch vụ thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

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
