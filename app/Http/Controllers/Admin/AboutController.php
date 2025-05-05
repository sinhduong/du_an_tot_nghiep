<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboutController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:abouts_list')->only(['index']);
        $this->middleware('permission:abouts_create')->only(['create', 'store']);
        $this->middleware('permission:abouts_update')->only(['edit', 'update']);
        $this->middleware('permission:abouts_delete')->only(['destroy']);
    }
    public function index()
    {
        $abouts = About::latest()->paginate(10);
        return view('admins.abouts.index', compact('abouts'));
    }

    public function create()
    {
        return view('admins.abouts.form');
    }

    public function store(Request $request)
    {
        $rules = [
            'about' => 'required|string',
            'is_use' => 'required|boolean',
        ];

        $request->validate($rules);

        DB::beginTransaction();
        try {
            if ($request->is_use) {
                $about = About::where('is_use', true);
                $about->update(['is_use' => false]);
            }

            About::create([
                'about' => $request->about,
                'is_use' => $request->is_use,
            ]);

            DB::commit();
            session()->flash('success', 'Tạo mới trang thành công!');
            return redirect()->route('admin.abouts.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            return redirect()->back();

        }
    }

    public function edit(About $about)
    {
        return view('admins.abouts.form', compact('about'));
    }

    public function update(Request $request, About $about)
    {
        $rules = [
            'about' => 'required|string',
            'is_use' => 'required|boolean',
        ];

        $request->validate($rules);

        DB::beginTransaction();
        try {
            if ($about->is_use && !$request->is_use) {
                session()->flash('error', 'Không thể thay đổi trạng thái từ "Đang sử dụng" sang "Không sử dụng"!');
                return redirect()->back();
            }

            if ($request->is_use) {
                About::where('is_use', true)
                    ->where('id', '!=', $about->id)
                    ->update(['is_use' => false]);
            }

            $about->update([
                'about' => $request->about,
                'is_use' => $request->is_use,
            ]);

            DB::commit();
            session()->flash('success', 'Cập nhật About thành công!');
            return redirect()->route('admin.abouts.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Có lỗi xảy ra khi cập nhật About: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy(About $about)
    {
        if ($about->is_use) {
            return redirect()->back()->with('error', 'Không thể xóa trang đang được sử dụng!');
        }
        $about->delete();
        return redirect()->route('admin.abouts.index')->with('success', 'Xóa About thành công!');
    }
}
