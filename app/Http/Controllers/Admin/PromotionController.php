<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromotionRequest;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:promotions_list')->only(['index']);
        $this->middleware('permission:promotions_create')->only(['create', 'store']);
        $this->middleware('permission:promotions_detail')->only(['show']);
        $this->middleware('permission:promotions_update')->only(['edit', 'update']);
        $this->middleware('permission:promotions_delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $query = Promotion::query();
        if (!empty($data['name'])) {
            $query->where('name', 'like', '%' . $data['name'] . '%');
        }

        $promotions = $query->orderBy('id', 'desc')
            ->paginate($data['size'] ?? 20);
        return view('admins.promotions.index', compact('promotions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admins.promotions.create");

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PromotionRequest $request)
    {
        try {
            $data = $request->all();
            Promotion::create($data);
            return redirect()->route('admin.promotions.index')->with('success', "Tạo mã giảm giá thành công");
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $isEdit = false;
        $promotion = Promotion::findOrFail($id);
        return view('admins.promotions.edit', compact('promotion', 'isEdit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $isEdit = true;
        $promotion = Promotion::findOrFail($id);
        return view('admins.promotions.edit', compact('promotion', 'isEdit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PromotionRequest $request, $id)
    {
        try {
            $data = $request->all();
            $promotion = Promotion::findOrFail($id);
            $promotion->update($data);
            return redirect()->route('admin.promotions.index')->with('success', "Cập nhật mã giảm giá thành công");
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', "Xóa mã giảm giá thành công");
    }
}
