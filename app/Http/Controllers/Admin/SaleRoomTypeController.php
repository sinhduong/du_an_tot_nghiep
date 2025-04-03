<?php

namespace App\Http\Controllers\Admin;

use App\Models\RoomType;
use App\Models\SaleRoomType;
use App\Http\Requests\SaleRoomTypeRequest;
use App\Http\Controllers\Controller;

class SaleRoomTypeController extends Controller
{
    public function index()
    {
        $saleRoomTypes = SaleRoomType::with('roomType')->get();
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
        SaleRoomType::create($request->validated());
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
        return view('admins.sale-roomType.edit', compact('saleRoomType', 'roomTypes'));
    }

    public function update(SaleRoomTypeRequest $request, SaleRoomType $saleRoomType)
    {
        
        $saleRoomType->update($request->validated());
        return redirect()->route('admin.sale-room-types.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy(SaleRoomType $saleRoomType)
    {
        $saleRoomType->delete();
        return redirect()->route('admin.sale-room-types.index')
            ->with('success', 'Xóa thành công');
    }

    public function toggleStatus(SaleRoomTypeRequest $request, SaleRoomType $saleRoomType)
    {
        $saleRoomType->update(['status' => $request->validated()['status']]);
        return response()->json(['success' => true, 'status' => $saleRoomType->status]);
    }
}
