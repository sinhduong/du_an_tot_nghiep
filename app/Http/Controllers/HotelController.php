<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Danh sách khách sạn';
        $listHotel = Hotel::orderBy('id', 'desc')->get();
        return view('admins.hotels.index', compact('title', 'listHotel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Thêm khách sạn';
        return view('admins.hotels.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHotelRequest $request)
    {
        if ($request->isMethod('POST')) {
            // Tạo hotel với dữ liệu từ request
            Hotel::create($request->only(['name', 'address', 'city', 'description', 'price_min', 'price_max']));

            // Chuyển hướng sau khi thêm thành công
            return redirect()->route('admin.hotels.index')->with('success', 'Thêm khách sạn thành công');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Hotel $hotel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Sửa hotel';
        $hotel = Hotel::findOrFail($id);
        return  view('admins.hotels.edit', compact('title', 'hotel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHotelRequest $request, string $id)
    {
        $hotel = Hotel::findOrFail($id);
        if ($request->isMethod('PUT')) {
            // Tạo hotel với dữ liệu từ request
            $hotel->update($request->only(['name', 'address', 'city', 'description', 'price_min', 'price_max']));

            // Chuyển hướng sau khi thêm thành công
            return redirect()->route('admin.hotels.index')->with('success', 'Sửa khách sạn thành công');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->delete();
        return  redirect()->route('admin.hotels.index')->with('success', 'Xóa thành công');
    }
}
