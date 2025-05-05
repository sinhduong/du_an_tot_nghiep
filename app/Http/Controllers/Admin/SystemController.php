<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoresystemRequest;
use App\Http\Requests\UpdatesystemRequest;
use App\Models\system;
use Illuminate\Support\Facades\Storage;

class SystemController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:systems_list')->only(['index']);
        $this->middleware('permission:systems_create')->only(['create', 'store']);
        $this->middleware('permission:systems_update')->only(['edit', 'update']);
        $this->middleware('permission:systems_delete')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $title = 'Danh Sách ';
        $systems = System::orderBy('id', 'desc')->get();
        // dd($systems->pluck('logo'));

        return view('admins.systems.index', compact('title', 'systems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Thêm  Mới';
        // $roomTypes = System::all();
        return view('admins.systems.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoresystemRequest $request)
    {
        //
        $data = $request->all();

        if ($request->hasFile('logo')) {
            $data['logo'] = Storage::disk('public')->put('systems', $request->file('logo'));
        }

        $systems = System::create($data);
        return redirect()->route('admin.systems.index')->with('success', 'Thêm  thành công');

    }

    /**
     * Display the specified resource.
     */
    public function show(system $system)
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
        $systems = System::findOrFail($id);
        return view('admins.systems.edit', compact('title', 'systems'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatesystemRequest $request, String $id)
    {
        //
        $data = $request->all();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('systems', 'public');
        }

        $systems = System::findOrFail($id);
        $systems->update($data);
        return redirect()->route('admin.systems.index')->with('success', 'Cập nhật thành công');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        //
        $systems = System::findOrFail($id);
        $systems->delete();
        return redirect()->route('admin.systems.index')->with('success', 'Xóa  thành công');

    }
}
