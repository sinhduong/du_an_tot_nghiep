<?php

namespace Database\Factories;

use App\Models\Staff;
use App\Models\StaffAttendance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StaffAttendance>
 */
class StaffAttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = StaffAttendance::class;

    public function definition()
    {
        $date = $this->faker->date();
        $checkIn = $this->faker->dateTimeBetween("$date 06:00:00", "$date 10:00:00");
        $checkOut = $this->faker->dateTimeBetween("$date 14:00:00", "$date 20:00:00");

        return [
            'staff_id' => Staff::inRandomOrder()->first()->id ?? Staff::factory(),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'date' => $date,
        ];
    }
}
