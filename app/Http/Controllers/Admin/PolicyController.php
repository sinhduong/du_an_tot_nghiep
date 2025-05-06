<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePolicyRequest;
use App\Http\Requests\UpdatePolicyRequest;
use App\Models\Policy;

class PolicyController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:policies_list')->only(['index']);
        $this->middleware('permission:policies_create')->only(['create', 'store']);
        $this->middleware('permission:policies_update')->only(['edit', 'update']);
        $this->middleware('permission:policies_delete')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $title = 'Danh Sách Chính Sách' ;
        $policy = Policy::paginate(10);
        return view('admins.policies.index', compact('title', 'policy'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $title = 'Danh Sách Chính Sách' ;
        // $policy = Policy::paginate(10);
        return view('admins.policies.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePolicyRequest $request)
    {
        //
        Policy::create($request->all());
        return redirect()->route('admin.policies.index')->with('success', 'Thêm dịch vụ thành công');


    }

    /**
     * Display the specified resource.
     */
    public function show(Policy $policy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $title = 'Sửa  Chính Sách' ;
        $policy = Policy::findOrFail($id);
        return view('admins.policies.edit', compact('title','policy'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePolicyRequest $request, string $id)
    {
        //
        $policy = Policy::findOrFail($id);

        $policy->update($request->all());
        return redirect()->route('admin.policies.index')->with('success', 'Cập nhật chính sách thành công');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $policy = Policy::findOrFail($id);
        $policy->delete();
        return redirect()->route('admin.policies.index')->with('success', 'Chính sách đã được xóa mềm thành công');

    }
}
