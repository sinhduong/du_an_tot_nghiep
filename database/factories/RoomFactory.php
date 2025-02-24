<?php

namespace Database\Factories;

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
            'name' => $this->faker->word(),
            'manager_id' => null,
            'room_number' => $this->faker->unique()->numberBetween(100, 999), // Thêm unique() vào đây
            'price' => $this->faker->randomFloat(2, 100, 1000),
            'max_capacity' => rand(1, 6),
            'bed_type' => $this->faker->randomElement(['single', 'double', 'queen', 'king', 'bunk', 'sofa']),
            'children_free_limit' => rand(0, 2),
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['available', 'booked', 'maintenance']),
            'room_type_id' => rand(1, 5),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
