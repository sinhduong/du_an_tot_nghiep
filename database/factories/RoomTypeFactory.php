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
            'name' => $this->faker->randomElement([
                'Phòng Deluxe',
                'Phòng Suite',
                'Phòng Family',
                'Phòng VIP',
            ]),
            'image' => $this->faker->imageUrl(640, 480, 'room'),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 100, 1000),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
