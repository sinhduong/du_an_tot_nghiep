<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hotel>
 */
class HotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . 'Hotel',
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'description' => $this->faker->paragraph(),
            'price_min' => $this->faker->numberBetween(50, 200),
            'price_max' => $this->faker->numberBetween(201, 500),
        ];
    }
}
