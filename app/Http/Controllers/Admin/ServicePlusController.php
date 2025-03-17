<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServicePlusFormRequest;
use App\Models\ServicePlus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            $data = $request->validated();
            // Debug dữ liệu
            Log::info('Validated data:', $data); // Hoặc dd($data);

            ServicePlus::create($data);

            alert()->success('Thành công', 'Dịch vụ đã được thêm thành công!');
            return redirect()->route('admin.service_plus.index');
        } catch (\Exception $exception) {
            alert()->error('Lỗi', 'Có lỗi xảy ra khi thêm dịch vụ: ' . $exception->getMessage());
            return redirect()->back()->withInput();
        }
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
            $service->update([
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'is_active' => $request->input('is_active', 1),
            ]);
            DB::commit();
            alert()->success('Thành công', 'Dịch vụ đã được cập nhật thành công!');
            return redirect()->route('admin.service_plus.index');
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error('Lỗi', 'Có lỗi xảy ra khi cập nhật dịch vụ: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $service = ServicePlus::findOrFail($id);
            $service->delete();
            alert()->success('Thành công', 'Dịch vụ đã được xóa !');
            return redirect()->route('admin.service_plus.index');
        } catch (\Exception $e) {
            alert()->error('Lỗi', 'Có lỗi xảy ra khi xóa dịch vụ: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
