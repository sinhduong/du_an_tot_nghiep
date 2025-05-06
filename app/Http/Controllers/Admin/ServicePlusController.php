<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServicePlusFormRequest;
use App\Models\ServicePlus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServicePlusController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:service_plus_list')->only(['index']);
        $this->middleware('permission:service_plus_create')->only(['create', 'store']);
        $this->middleware('permission:service_plus_update')->only(['edit', 'update']);
        $this->middleware('permission:service_plus_delete')->only(['destroy']);
    }
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
            $data = $request->validated();
            Log::info('Validated data:', $data);

            $service = ServicePlus::create($data);

            return redirect()->route('admin.service_plus.index')
                ->with('success', "Thêm dịch vụ thành công! Dịch vụ {$service->name} đã được thêm.");
        } catch (\Exception $exception) {
            return redirect()->back()
                ->with('error', 'Thêm dịch vụ thất bại! Có lỗi xảy ra: ' . $exception->getMessage())
                ->withInput();
        }
    }

    public function edit(string $id)
    {
        $title = 'Sửa dịch vụ';
        $service = ServicePlus::findOrFail($id);
        return view('admins.service_plus.edit', compact('service', 'title'));
    }

    public function update(ServicePlusFormRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $service = ServicePlus::findOrFail($id);
            $service->update($request->validated()); // Sử dụng dữ liệu đã validate
            DB::commit();

            return redirect()->route('admin.service_plus.index')
                ->with('success', "Cập nhật dịch vụ thành công! Dịch vụ {$service->name} đã được cập nhật.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Cập nhật dịch vụ thất bại! Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $service = ServicePlus::findOrFail($id);
            $service->delete();

            return redirect()->route('admin.service_plus.index')
                ->with('success', "Xóa dịch vụ thành công! Dịch vụ {$service->name} đã được xóa.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Xóa dịch vụ thất bại! Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
