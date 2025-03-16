<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Admin\RoomType;
use App\Models\Admin\Service;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{

    public function index()
    {
        $title = 'Danh sách dịch vụ';
        $services = Service::paginate(10);
        return view('admins.services.index', compact('title', 'services'));
    }

    public function create()
    {
        $title = 'Thêm dịch vụ';
        $roomTypes = RoomType::all();
        return view('admins.services.create', compact('title', 'roomTypes'));
    }

    public function store(StoreServiceRequest $request)
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

    public function show(Service $service)
    {
        //
    }

    public function edit(string $id)
    {
        $title = 'Sửa dịch vụ';
        $roomTypes = RoomType::all();
        $service = Service::findOrFail($id);
        $selectedRoomTypes = $service->roomTypes->pluck('id')->toArray();
        return view('admins.services.edit', compact('service', 'title', 'roomTypes', 'selectedRoomTypes'));
    }

    public function update(UpdateServiceRequest $request, string $id)
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
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Dịch vụ đã được xóa mềm');
    }
}
