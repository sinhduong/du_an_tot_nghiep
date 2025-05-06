<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class AdminAccountController extends BaseAdminController
{
    public function __construct(){
        $this->middleware('permission:admin_accounts_list')->only(['index']);
        $this->middleware('permission:admin_accounts_edit')->only(['edit', 'update']);
    }

    public function index()
    {
        $title = 'Tài khoản quản trị viên';
        $admins = User::role('admin')->get();
        return view('admins.admin_accounts.index', compact('admins', 'title'));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function edit(User $admin, $id)
    {
        $title = 'Chỉnh sửa tài khoản quản trị viên';
        $admin = User::role('admin', 'management')->where('id',$id)->first();
        return view('admins.admin_accounts.edit', compact('admin', 'title'));
    }

    public function update(Request $request, $id)
    {
        $admin = User::role('admin', 'management')->where('id', $id)->first();
        $admin->update([
            'is_active' => $request->is_active
        ]);

        return redirect()->route('admin.admin_accounts.index')
            ->with('success', 'Cập nhật trạng thái thành công.');
    }

    public function destroy(User $admin)
    {
    }
}
