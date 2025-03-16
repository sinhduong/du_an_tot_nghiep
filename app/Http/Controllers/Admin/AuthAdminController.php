<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\User;

class AuthAdminController extends Controller
{
    public function index()
    {
        $title = 'Danh sách tài khoản';
        $superAdmins = User::orderBy('id', 'desc')->get();
        return view('admins.roles.index', compact('title', 'superAdmins'));
    }
}
