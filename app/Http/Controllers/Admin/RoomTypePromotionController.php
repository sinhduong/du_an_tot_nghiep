<?php

namespace App\Http\Controllers\Admin;

use App\Models\RoomType;
use App\Models\Promotion;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoomTypePromotionRelationshipRequest;

class RoomTypePromotionController extends BaseAdminController
{
    public function index()
    {
        $title='Quản lý mối quan hệ giữa Loại phòng và Khuyến mãi';
        $relationships = DB::table('promotion_room_type')
            ->join('room_types', 'promotion_room_type.room_type_id', '=', 'room_types.id')
            ->join('promotions', 'promotion_room_type.promotion_id', '=', 'promotions.id')
            ->select('promotion_room_type.*', 'room_types.name as room_type_name', 'promotions.name as promotion_name')
            ->get();

        return view('admins.roomTypePromotion.index', compact('relationships','title'));
    }

    public function create()
    {
        $roomTypes = RoomType::where('is_active', true)->get();
        $promotions = Promotion::where('status', 'active')
            ->where('end_date', '>=', now())
            ->get();

        return view('admins.roomTypePromotion.create', compact('roomTypes', 'promotions'));
    }

    public function store(RoomTypePromotionRelationshipRequest $request)
    {
        DB::table('promotion_room_type')->insert([
            'room_type_id' => $request->room_type_id,
            'promotion_id' => $request->promotion_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.room_types_promotion.index')
            ->with('success', 'Đã thêm mối quan hệ thành công.');
    }

    public function show($id)
    {
        $relationship = DB::table('promotion_room_type')
            ->join('room_types', 'promotion_room_type.room_type_id', '=', 'room_types.id')
            ->join('promotions', 'promotion_room_type.promotion_id', '=', 'promotions.id')
            ->select('promotion_room_type.*', 'room_types.name as room_type_name', 'promotions.name as promotion_name')
            ->where('promotion_room_type.id', $id)
            ->first();

        if (!$relationship) {
            return redirect()->route('admin.room_types_promotion.index')
                ->with('error', 'Mối quan hệ không tồn tại.');
        }

        return view('admins.roomTypePromotion.show', compact('relationship'));
    }

    public function edit($id)
    {
        $relationship = DB::table('promotion_room_type')
            ->where('id', $id)
            ->first();

        if (!$relationship) {
            return redirect()->route('admin.room_types_promotion.index')
                ->with('error', 'Mối quan hệ không tồn tại.');
        }

        $roomTypes = RoomType::where('is_active', true)->get();
        $promotions = Promotion::where('status', 'active')
            ->where('end_date', '>=', now())
            ->get();

        return view('admins.roomTypePromotion.edit', compact('relationship', 'roomTypes', 'promotions'));
    }

    public function update(RoomTypePromotionRelationshipRequest $request, $id)
    {
        $relationship = DB::table('promotion_room_type')
            ->where('id', $id)
            ->first();

        if (!$relationship) {
            return redirect()->route('admin.room_types_promotion.index')
                ->with('error', 'Mối quan hệ không tồn tại.');
        }

        DB::table('promotion_room_type')
            ->where('id', $id)
            ->update([
                'room_type_id' => $request->room_type_id,
                'promotion_id' => $request->promotion_id,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.room_types_promotion.index')
            ->with('success', 'Đã cập nhật mối quan hệ thành công.');
    }

    public function destroy($id)
    {
        $relationship = DB::table('promotion_room_type')
            ->where('id', $id)
            ->first();

        if (!$relationship) {
            return redirect()->route('admin.room_types_promotion.index')
                ->with('error', 'Mối quan hệ không tồn tại.');
        }

        DB::table('promotion_room_type')
            ->where('id', $id)
            ->delete();

        return redirect()->route('admin.room_types_promotion.index')
            ->with('success', 'Đã xóa mối quan hệ thành công.');
    }
}
