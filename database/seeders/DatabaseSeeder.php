<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\StaffShift;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Review::factory(10)->create();
        User::factory(5)->create();
        StaffShift::factory(5)->create();
        // StaffRole::factory(5)->create();
        // if (Staff::count() === 0) {
        //     \App\Models\Staff::factory(5)->create();
        // }

        // Tạo dữ liệu chấm công ngẫu nhiên
        // StaffAttendance::factory(5)->create();


        // // Tạo vai trò
        // $adminRole = StaffRole::create([
        //     'name' => 'Admin',
        //     'permissions' => json_encode(['manage_staffs' => true])
        // ]);

        // $staffRole = StaffRole::create([
        //     'name' => 'Nhân viên',
        //     'permissions' => json_encode(['manage_staffs' => false])
        // ]);

        // // Tạo ca làm việc
        // $shift = StaffShift::create([
        //     'name' => 'Ca sáng',
        //     'start_time' => '08:00:00',
        //     'end_time' => '16:00:00'
        // ]);

        // // Tạo nhân viên mẫu
        // Staff::create([
        //     'user_id' => '3',
        //     'role_id' => $adminRole->id,
        //     'shift_id' => $shift->id,
        //     'status' => 'active',
        //     'notes' => 'hieu dep trai nhat the gioi'
        // ]);

        $this->call([
            RefundPolicySeeder::class,
        ]);
    }
}
