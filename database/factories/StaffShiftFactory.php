<?php

namespace Database\Factories;

use App\Models\StaffShift;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StaffShift>
 */
class StaffShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = StaffShift::class;

    public function definition()
    {
        $startTime = $this->faker->randomElement(['06:00:00', '14:00:00', '22:00:00']); // Giờ bắt đầu
        $endTime = date('H:i:s', strtotime($startTime . ' +8 hours')); // Giả sử mỗi ca kéo dài 8 tiếng

        return [
            'name' => $this->faker->randomElement(['Ca sáng', 'Ca chiều', 'Ca đêm']),
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];
    }
}
