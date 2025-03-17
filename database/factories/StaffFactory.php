<?php

namespace Database\Factories;

use App\Models\Staff;
use App\Models\StaffRole;
use App\Models\StaffShift;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffFactory extends Factory
{
    protected $model = Staff::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Tạo user mới hoặc lấy ID có sẵn
            'role_id' => StaffRole::inRandomOrder()->first()->id ?? StaffRole::factory(),
            'shift_id' => StaffShift::inRandomOrder()->first()->id ?? null,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'notes' => $this->faker->sentence(),
        ];
    }
}
