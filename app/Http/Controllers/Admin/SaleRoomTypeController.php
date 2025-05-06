<?php

namespace App\Http\Controllers\Admin;

use App\Models\RoomType;
use App\Models\SaleRoomType;
use App\Http\Requests\SaleRoomTypeRequest;
use App\Http\Controllers\Controller;

class SaleRoomTypeController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:sale_room_types_list')->only(['index']);
        $this->middleware('permission:sale_room_types_create')->only(['create', 'store']);
        $this->middleware('permission:sale_room_types_detail')->only(['show']);
        $this->middleware('permission:sale_room_types_update')->only(['edit', 'update']);
        $this->middleware('permission:sale_room_types_delete')->only(['destroy']);
    }
    public function index()
    {
        // Lấy tất cả các bản ghi SaleRoomType và nhóm theo name để hiển thị nhiều loại phòng
        $saleRoomTypes = SaleRoomType::with('roomType')->get()->groupBy('name');
        $title = 'Danh sách mối quan hệ Loại phòng - Khuyến mãi';
        return view('admins.sale-roomType.index', compact('saleRoomTypes', 'title'));
    }

    public function create()
    {
        $roomTypes = RoomType::all();
        return view('admins.sale-roomType.create', compact('roomTypes'));
    }

    public function store(SaleRoomTypeRequest $request)
    {
        $validated = $request->validated();

        // Tạo một bản ghi SaleRoomType cho mỗi room_type_id
        foreach ($validated['room_type_ids'] as $roomTypeId) {
            SaleRoomType::create([
                'name' => $validated['name'],
                'value' => $validated['value'],
                'type' => $validated['type'],
                'room_type_id' => $roomTypeId,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => $validated['status'],
            ]);
        }

        return redirect()->route('admin.sale-room-types.index')
            ->with('success', 'Thêm mới thành công');
    }

    public function show(SaleRoomType $saleRoomType)
    {
        return view('admins.sale-roomType.show', compact('saleRoomType'));
    }

    public function edit(SaleRoomType $saleRoomType)
    {
        $roomTypes = RoomType::all();
        // Lấy tất cả các bản ghi SaleRoomType có cùng name để hiển thị các loại phòng đã chọn
        $relatedSaleRoomTypes = SaleRoomType::where('name', $saleRoomType->name)->pluck('room_type_id')->toArray();
        return view('admins.sale-roomType.edit', compact('saleRoomType', 'roomTypes', 'relatedSaleRoomTypes'));
    }

    public function update(SaleRoomTypeRequest $request, SaleRoomType $saleRoomType)
    {
        $validated = $request->validated();

        // Xóa các bản ghi cũ có cùng name
        SaleRoomType::where('name', $saleRoomType->name)->delete();

        // Tạo lại các bản ghi mới cho mỗi room_type_id
        foreach ($validated['room_type_ids'] as $roomTypeId) {
            SaleRoomType::create([
                'name' => $validated['name'],
                'value' => $validated['value'],
                'type' => $validated['type'],
                'room_type_id' => $roomTypeId,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => $validated['status'],
            ]);
        }

        return redirect()->route('admin.sale-room-types.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy(SaleRoomType $saleRoomType)
    {
        // Xóa tất cả các bản ghi có cùng name
        SaleRoomType::where('name', $saleRoomType->name)->delete();
        return redirect()->route('admin.sale-room-types.index')
            ->with('success', 'Xóa thành công');
    }


}
