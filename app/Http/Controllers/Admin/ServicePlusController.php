<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServicePlusFormRequest;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\RoomType;
use App\Models\Service;
use App\Models\ServicePlus;
use Illuminate\Support\Facades\DB;

class ServicePlusController extends Controller
{

    public function index()
    {
        $title = 'Danh sách dịch vụ';
        $services = ServicePlus::paginate(10);
        return view('admins.service_plus.index', compact('title', 'services'));
    }

    public function create()
    {
        $title = 'Thêm dịch vụ';
        return view('admins.service_plus.create', compact('title'));
    }

    public function store(ServicePlusFormRequest $request)
    {
        try {
            DB::beginTransaction();
            $service = ServicePlus::create($request->all());
            DB::commit();
            return redirect()->route('admin.service_plus.index')->with('success', 'Thêm dịch vụ thành công');
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
        $service = ServicePlus::findOrFail($id);
        return view('admins.service_plus.edit', compact('service', 'title'));
    }

    public function update(ServicePlusFormRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $service = ServicePlus::findOrFail($id);

            $service->update($request->all());
            DB::commit();
            return redirect()->route('admin.service_plus.index')->with('success', 'Cập nhật dịch vụ thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy($id)
    {
        $service = ServicePlus::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.service_plus.index')->with('success', 'Dịch vụ đã được xóa mềm');
    }
}
