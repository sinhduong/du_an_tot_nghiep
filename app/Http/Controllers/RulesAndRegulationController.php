<?php

namespace App\Http\Controllers;

use App\Models\rules_and_regulation;
use App\Http\Requests\Storerules_and_regulationRequest;
use App\Http\Requests\Updaterules_and_regulationRequest;

class RulesAndRegulationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Các Quy Định ';
        $room_rule = rules_and_regulation::orderBy('id', 'desc')->get();
        return view('admins.rule-regulation.index', compact('title', 'room_rule'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Thêm Quy Tắc ';
        return  view('admins.rule-regulation.create', compact('title'));
    }

    //     /**
    //      * Store a newly created resource in storage.
    //      */
    public function store(Storerules_and_regulationRequest $request)
    {
        if ($request->isMethod('POST')) {
            // Lấy dữ liệu từ request
            $data = $request->except('_token');

            // Thêm loại phòng vào database
            rules_and_regulation::create($data);
        }

        // Chuyển hướng về danh sách với thông báo thành công
        return redirect()->route('admin.rule-regulations.index')->with('success', 'Thêm quy định phòng thành công');
    }


    //     /**
    //      * Display the specified resource.
    //      */
    //     public function show(RoomType $room_type)
    //     {
    //         //
    //     }

    //     /**
    //      * Show the form for editing the specified resource.
    //      */
    public function edit(string $id)
    {
        $title = 'Sửa loại phòng';
        $room_types = rules_and_regulation::findOrfail($id);
        return  view('admins.rule-regulation.edit', compact('room_types', 'title'));
    }

    //     /**
    //      * Update the specified resource in storage.
    //      */
    public function update(Updaterules_and_regulationRequest $request, string $id)
    {
        // Tìm loại phòng theo ID, nếu không có sẽ báo lỗi 404
        $room_type = rules_and_regulation::findOrFail($id);

        // Lấy dữ liệu từ request, loại bỏ _token và _method
        $data = $request->except('_token', '_method');

        // Cập nhật loại phòng
        $room_type->update($data);

        // Chuyển hướng về danh sách với thông báo thành công
        return redirect()->route('admin.rule-regulations.index')->with('success', 'Cập nhật loại phòng thành công');
    }


    //     /**
    //      * Remove the specified resource from storage.
    //      */
    public function destroy($id)
    {
        $room_type = rules_and_regulation::findOrFail($id);
        $room_type->delete(); // Xóa mềm

        return redirect()->route('admin.rule-regulations.index')->with('success', 'Loại phòng đã được xóa mềm');
    }



    public function trashed()
    {
        $title = 'Loại phòng đã xóa';
        $room_types = rules_and_regulation::onlyTrashed()->get();
        return view('admins.rule-regulation.trashed', compact('title', 'room_types'));
    }

    public function restore($id)
    {
        $room_type = rules_and_regulation::onlyTrashed()->findOrFail($id);
        $room_type->restore(); // Khôi phục
        return redirect()->route('admin.rule-regulations.index')->with('success', 'Khôi phục loại phòng thành công');
    }

    public function forceDelete($id)
    {
        $room_type = rules_and_regulation::onlyTrashed()->findOrFail($id);
        $room_type->forceDelete(); // Xóa vĩnh viễn
        return redirect()->route('admin.rule-regulations.trashed')->with('success', 'Xóa vĩnh viễn loại phòng thành công');
    }
}
