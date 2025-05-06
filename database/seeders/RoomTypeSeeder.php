<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default "Unknown" room type
        RoomType::create([
            'name' => 'Không xác định',
            'description' => 'Loại phòng mặc định cho các phòng chưa được phân loại',
            'price' => 0,
            'max_capacity' => 4,
            'size' => 0,
            'bed_type' => 'single',
            'children_free_limit' => 0,
            'is_active' => true,
        ]);
    }
}
