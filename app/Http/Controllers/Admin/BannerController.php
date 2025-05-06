<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:banners_list')->only(['index']);
        $this->middleware('permission:banners_create')->only(['create', 'store']);
        $this->middleware('permission:banners_update')->only(['edit', 'update']);
        $this->middleware('permission:banners_delete')->only(['destroy']);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $title = 'Danh Sách Banner ';
        $banners = Banner::orderBy('id', 'desc')->get();
// dd($banners->pluck('image'));

        return view('admins.banners.index', compact('title', 'banners'));


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $title = 'Thêm Mới  Banner ';
        return view('admins.banners.create', compact('title'));


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBannerRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = Storage::disk('public')->put('banners', $request->file('image'));
        }

        $banner = Banner::create($data);
        return redirect()->route('admin.banners.index')->with('success', 'Thêm banner thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        //
        $title = 'Danh Sách Banner ';
        $banners = Banner::findOrFail($id);
        return view('admins.banners.edit', compact('title', 'banners'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerRequest $request,String $id)
    {
        //
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner = Banner::findOrFail($id);
        $banner->update($data);
        return redirect()->route('admin.banners.index')->with('success', 'Cập nhật banner thành công');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        //
        $banner = Banner::findOrFail($id);
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Xóa banner thành công');

    }
}
