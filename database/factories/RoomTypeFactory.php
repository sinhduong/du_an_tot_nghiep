<?php

namespace Database\Factories;

use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomTypeFactory extends Factory
{
    protected $model = RoomType::class;

    /**
     * Define the model's default state.
     */

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word . ' Room', // Ví dụ: Deluxe Room
            'description' => $this->faker->sentence(10), // Mô tả ngẫu nhiên
            'price' => $this->faker->randomFloat(2, 50, 500), // Giá ngẫu nhiên từ 50 đến 500
            'max_capacity' => $this->faker->numberBetween(1, 6), // Tối đa từ 1 đến 6 người
            'size' => $this->faker->randomFloat(2, 15, 100), // Diện tích từ 15 đến 100 m²
            'bed_type' => $this->faker->randomElement(['single', 'double', 'queen', 'king', 'bunk', 'sofa']),
            'children_free_limit' => $this->faker->numberBetween(0, 2), // Từ 0 đến 2 trẻ miễn phí
            'is_active' => $this->faker->boolean(80), // 80% là active
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
