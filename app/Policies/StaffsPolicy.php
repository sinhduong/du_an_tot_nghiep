<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\User;

class StaffsPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Cho phép tất cả người dùng
    }

    public function view(User $user, Staff $staff): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true; // Cho phép tất cả người dùng thêm nhân viên
    }

    public function update(User $user, Staff $staff): bool
    {
        return true;
    }

    public function delete(User $user, Staff $staff): bool
    {
        return true;
    }

    public function restore(User $user, Staff $staff): bool
    {
        return true;
    }

    public function forceDelete(User $user, Staff $staff): bool
    {
        return true;
    }
}
