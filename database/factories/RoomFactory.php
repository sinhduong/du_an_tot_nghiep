<?php

namespace Database\Factories;

use App\Models\RoomType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\rooms>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_number' => $this->faker->unique()->numberBetween(100, 999), // Đảm bảo giá trị duy nhất
            'manager_id' => User::inRandomOrder()->value('id') ?? null,
            'status' => $this->faker->randomElement(['available', 'booked', 'maintenance']),
            'room_type_id' => RoomType::inRandomOrder()->value('id') ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
