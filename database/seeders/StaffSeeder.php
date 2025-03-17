<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    Staff::factory(10)->create(); // Tạo 10 nhân viên giả
}

}
