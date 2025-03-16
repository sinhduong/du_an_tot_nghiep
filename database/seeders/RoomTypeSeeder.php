<?php

namespace Database\Seeders;

use App\Models\Admin\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoomType::factory(10)->create(); // Tạo 10 loại phòng giả
    }
}
