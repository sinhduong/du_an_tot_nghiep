<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;

class ServiceController extends Controller
{

    public function index()
    {
        $title = 'Danh sách dịch vụ';
        $services = Service::orderBy('id', 'desc')->get();
        return view('admins.services.index', compact('title', 'services'));
    }

    public function create()
    {
        $title = 'Thêm dịch vụ';
        return view('admins.services.create', compact('title'));
    }

    public function store(StoreServiceRequest $request)
    {
        if ($request->isMethod('POST')) {
            $data = $request->except('_token');

            // Thêm dịch vụ vào database
            Service::create($data);
        }

        return redirect()->route('admin.services.index')->with('success', 'Thêm dịch vụ thành công');
    }

    public function show(Service $service)
    {
        //
    }

    public function edit(string $id)
    {
        $title = 'Sửa dịch vụ';
        $service = Service::findOrFail($id);
        return view('admins.services.edit', compact('service', 'title'));
    }

    public function update(UpdateServiceRequest $request, string $id)
    {
        $service = Service::findOrFail($id);
        $data = $request->except('_token', '_method');

        $service->update($data);

        return redirect()->route('admin.services.index')->with('success', 'Cập nhật dịch vụ thành công');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete(); // Xóa mềm

        return redirect()->route('admin.services.index')->with('success', 'Dịch vụ đã được xóa mềm');
    }

    public function trashed()
    {
        $title = 'Dịch vụ đã xóa';
        $services = Service::onlyTrashed()->get();
        return view('admins.services.trashed', compact('title', 'services'));
    }

    public function restore($id)
    {
        $service = Service::onlyTrashed()->findOrFail($id);
        $service->restore(); // Khôi phục
        return redirect()->route('admin.services.index')->with('success', 'Khôi phục dịch vụ thành công');
    }

    public function forceDelete($id)
    {
        $service = Service::onlyTrashed()->findOrFail($id);
        $service->forceDelete(); // Xóa vĩnh viễn
        return redirect()->route('admin.services.trashed')->with('success', 'Xóa vĩnh viễn dịch vụ thành công');
    }
}
