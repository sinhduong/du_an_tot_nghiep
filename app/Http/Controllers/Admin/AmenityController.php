<?php

namespace App\Http\Controllers\Admin;

use App\Models\Amenity;
use App\Models\RoomType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\AmenityRequest;

class AmenityController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:amenities_list')->only(['index']);
        $this->middleware('permission:amenities_create')->only(['create', 'store']);
        $this->middleware('permission:amenities_detail')->only(['show']);
        $this->middleware('permission:amenities_update')->only(['edit', 'update']);
        $this->middleware('permission:amenities_delete')->only(['destroy']);
        $this->middleware('permission:amenities_trashed')->only(['trashed']);
        $this->middleware('permission:amenities_restore')->only(['restore']);
        $this->middleware('permission:amenities_force_delete')->only(['forceDelete']);
    }
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

    public function store(AmenityRequest $request)
    {
        try {
            DB::beginTransaction();
            $amenity = Amenity::create($request->all());
            if ($request->has('roomTypes')) {
                $amenity->roomTypes()->sync($request->roomTypes);
            }
            DB::commit();
            return redirect()->route('admin.amenities.index')
                ->with('success', "Thêm tiện nghi thành công! Tiện nghi {$amenity->name} đã được thêm.");
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error in AmenityController@store: ' . $exception->getMessage());
            return redirect()->back()
                ->with('error', 'Thêm tiện nghi thất bại! Có lỗi xảy ra: ' . $exception->getMessage())
                ->withInput();
        }
    }

    public function show(string $id)
    {
        $title = 'Chi tiết Tiện nghi';
        $amenity = Amenity::with('roomTypes')->findOrFail($id);
        return view('admins.amenities.show', compact('amenity', 'title'));
    }

    public function edit(string $id)
    {
        $title = 'Sửa Tiện nghi';
        $amenity = Amenity::findOrFail($id);
        $roomTypes = RoomType::all();
        $selectedRoomTypes = $amenity->roomTypes->pluck('id')->toArray();
        return view('admins.amenities.edit', compact('amenity', 'title', 'roomTypes', 'selectedRoomTypes'));
    }

    public function update(AmenityRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $amenity = Amenity::findOrFail($id);
            $amenity->update($request->all());
            if ($request->has('roomTypes')) {
                $amenity->roomTypes()->sync($request->roomTypes);
            }
            DB::commit();
            return redirect()->route('admin.amenities.index')
                ->with('success', "Cập nhật tiện nghi thành công! Tiện nghi {$amenity->name} đã được cập nhật.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in AmenityController@update: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Cập nhật tiện nghi thất bại! Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $amenity = Amenity::findOrFail($id);
            $amenity->delete();
            return redirect()->route('admin.amenities.trashed')
                ->with('success', "Xóa tiện nghi thành công! Tiện nghi {$amenity->name} đã được xóa mềm.");
        } catch (\Exception $e) {
            Log::error('Error in AmenityController@destroy: ' . $e->getMessage());
            return redirect()->route('admin.amenities.index')
                ->with('error', 'Xóa tiện nghi thất bại! Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function trashed()
    {
        $title = 'Danh Sách Tiện nghi Đã Xóa';
        $amenities = Amenity::onlyTrashed()->get();
        return view('admins.amenities.trashed', compact('title', 'amenities'));
    }

    public function restore($id)
    {
        try {
            $amenity = Amenity::onlyTrashed()->findOrFail($id);
            $amenity->restore();
            return redirect()->route('admin.amenities.index')
                ->with('success', "Khôi phục tiện nghi thành công! Tiện nghi {$amenity->name} đã được khôi phục.");
        } catch (\Exception $e) {
            Log::error('Error in AmenityController@restore: ' . $e->getMessage());
            return redirect()->route('admin.amenities.trashed')
                ->with('error', 'Khôi phục tiện nghi thất bại! Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $amenity = Amenity::onlyTrashed()->findOrFail($id);
            $amenity->forceDelete();
            return redirect()->route('admin.amenities.trashed')
                ->with('success', "Xóa vĩnh viễn tiện nghi thành công! Tiện nghi {$amenity->name} đã được xóa vĩnh viễn.");
        } catch (\Exception $e) {
            Log::error('Error in AmenityController@forceDelete: ' . $e->getMessage());
            return redirect()->route('admin.amenities.trashed')
                ->with('error', 'Xóa vĩnh viễn tiện nghi thất bại! Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
