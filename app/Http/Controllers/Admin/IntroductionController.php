<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Introduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IntroductionController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:introductions_list')->only(['index']);
        $this->middleware('permission:introductions_create')->only(['create', 'store']);
        $this->middleware('permission:introductions_update')->only(['edit', 'update']);
        $this->middleware('permission:introductions_delete')->only(['destroy']);
    }
    
    public function index()
    {
        $introductions = Introduction::latest()->paginate(10);
        return view('admins.introductions.index', compact('introductions'));
    }

    public function create()
    {
        return view('admins.introductions.form');
    }

    public function store(Request $request)
    {
        $rules = [
            'introduction' => 'required|string',
            'is_use' => 'required|boolean',
        ];

        $request->validate($rules);

        DB::beginTransaction();
        try {
            if ($request->is_use) {
                $introduction = Introduction::where('is_use', true);
                $introduction->update(['is_use' => false]);
            }

            Introduction::create([
                'introduction' => $request->introduction,
                'is_use' => $request->is_use,
            ]);

            DB::commit();
            session()->flash('success', 'Tạo mới trang giới thiệu thành công!');
            return redirect()->route('admin.introductions.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            return redirect()->back();

        }
    }

    public function edit(Introduction $introduction)
    {
        return view('admins.introductions.form', compact('introduction'));
    }

    public function update(Request $request, Introduction $introduction)
    {
        $rules = [
            'introduction' => 'required|string',
            'is_use' => 'required|boolean',
        ];

        $request->validate($rules);

        DB::beginTransaction();
        try {
            if ($introduction->is_use && !$request->is_use) {
                session()->flash('error', 'Không thể thay đổi trạng thái từ "Đang sử dụng" sang "Không sử dụng"!');
                return redirect()->back();
            }

            if ($request->is_use) {
                Introduction::where('is_use', true)
                    ->where('id', '!=', $introduction->id)
                    ->update(['is_use' => false]);
            }

            $introduction->update([
                'introduction' => $request->introduction,
                'is_use' => $request->is_use,
            ]);

            DB::commit();
            session()->flash('success', 'Cập nhật trang giới thiệu thành công!');
            return redirect()->route('admin.introductions.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Có lỗi xảy ra khi cập nhật trang giới thiệu: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy(Introduction $introduction)
    {
        if ($introduction->is_use) {
            return redirect()->back()->with('error', 'Không thể xóa trang đang được sử dụng!');
        }
        $introduction->delete();
        return redirect()->route('admin.introductions.index')->with('success', 'Xóa trang giới thiệu thành công!');
    }
}
