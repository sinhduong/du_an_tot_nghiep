<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use App\Models\RoomType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;

class ServiceController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:services_list')->only(['index']);//
        $this->middleware('permission:services_create')->only(['create', 'store']);//
        $this->middleware('permission:services_detail')->only(['show']);
        $this->middleware('permission:services_update')->only(['edit', 'update']);
        $this->middleware('permission:services_delete')->only(['destroy']);//
        $this->middleware('permission:services_trashed')->only(['trashed']);
        $this->middleware('permission:services_restore')->only(['restore']);
        $this->middleware('permission:services_force_delete')->only(['forceDelete']);
    }

    public function index()
    {
        $title = 'Danh sách dịch vụ';
        $services = Service::with('roomTypes')->paginate(10);
        return view('admins.services.index', compact('title', 'services'));
    }

    public function create()
    {
        $title = 'Thêm dịch vụ';
        $roomTypes = RoomType::all();
        return view('admins.services.create', compact('title', 'roomTypes'));
    }

    public function store(ServiceRequest $request)
    {
        try {
            DB::beginTransaction();
            $service = Service::create($request->all());
            if ($request->has('roomTypes')) {
                $service->roomTypes()->sync($request->roomTypes);
            }
            DB::commit();
            return redirect()->route('admin.services.index')->with('success', 'Thêm dịch vụ thành công');
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function show(string $id)
    {
        $title = 'Chi tiết dịch vụ';
        $service = Service::with('roomTypes')->findOrFail($id);
        return view('admins.services.show', compact('service', 'title'));
    }

    public function edit(string $id)
    {
        $title = 'Sửa dịch vụ';
        $roomTypes = RoomType::all();
        $service = Service::findOrFail($id);
        $selectedRoomTypes = $service->roomTypes->pluck('id')->toArray();
        return view('admins.services.edit', compact('service', 'title', 'roomTypes', 'selectedRoomTypes'));
    }

    public function update(ServiceRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $service = Service::findOrFail($id);
            $service->update($request->all());
            if ($request->has('roomTypes')) {
                $service->roomTypes()->sync($request->roomTypes);
            }
            DB::commit();
            return redirect()->route('admin.services.index')->with('success', 'Cập nhật dịch vụ thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->delete();

            return redirect()->route('admin.services.index')->with('success', 'Dịch vụ đã được xóa mềm thành công');
        } catch (\Exception $e) {
            Log::error('Error in ServiceController@destroy: ' . $e->getMessage());
            return redirect()->route('admin.services.trashed')->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Hiển thị danh sách các bản ghi đã bị xóa mềm (thùng rác)
    public function trashed()
    {
        $title = 'Thùng rác dịch vụ';
        $services = Service::onlyTrashed()->with('roomTypes')->paginate(10);
        return view('admins.services.trashed', compact('title', 'services'));
    }

    // Khôi phục bản ghi đã bị xóa mềm
    public function restore($id)
    {
        try {
            $service = Service::onlyTrashed()->findOrFail($id);
            $service->restore();

            return redirect()->route('admin.services.trashed')->with('success', 'Khôi phục dịch vụ thành công');
        } catch (\Exception $e) {
            Log::error('Error in ServiceController@restore: ' . $e->getMessage());
            return redirect()->route('admin.services.index')->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Xóa vĩnh viễn bản ghi
    public function forceDelete($id)
    {
        try {
            $service = Service::onlyTrashed()->findOrFail($id);

            // Xóa quan hệ với roomTypes trong bảng trung gian
            $service->roomTypes()->detach();

            // Xóa vĩnh viễn dịch vụ
            $service->forceDelete();

            return redirect()->route('admin.services.trashed')->with('success', 'Xóa vĩnh viễn dịch vụ thành công');
        } catch (\Exception $e) {
            Log::error('Error in ServiceController@forceDelete: ' . $e->getMessage());
            return redirect()->route('admin.services.trashed')->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
