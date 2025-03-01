<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:list_role')->only(['index']);
//        $this->middleware('permission:create_role')->only(['create', 'store']);
//        $this->middleware('permission:show_role')->only(['show']);
//        $this->middleware('permission:update_role')->only(['edit', 'update']);
//        $this->middleware('permission:destroy_role')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        return view('admins.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permission_groups = Permission::get()->groupBy('guard_name');
        return view('admins.roles.form', compact('permission_groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            DB::beginTransaction();

            $role = Role::create($data);
            if ($data['permissions']) {
                $role->permissions()->attach($data['permissions']);
            }

            DB::commit();
            return redirect()->route('admin.roles.index')->with('success', "Tạo mới thành công");
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $permission_groups = Permission::get()->groupBy('guard_name');
        return view('admins.roles.form', compact('role', 'permission_groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = $request->all();
            DB::beginTransaction();

            $role = Role::findOrFail($id);

            $role->update($data);
            if ($data['permissions']) {
                $role->permissions()->sync($data['permissions']);
            }

            DB::commit();
            return redirect()->route('admin.roles.index')->with('success', "Cập nhật thành công");
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        if ($role->name == "super admin" || $role->name == "admin") {
            return redirect()->back()->with('error', "Không thể xóa vai trò này");;
        }
        $check = $role->users()->count();
        if ($check > 0) {
            return redirect()->back()->with('error', "Không thể xóa vai trò vì đã có người dùng");;
        }

        $role->delete();
        return redirect()->back()->with('success', "Xóa vai trò thành công");
    }
}
