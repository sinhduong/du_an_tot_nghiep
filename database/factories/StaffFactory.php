<?php

namespace Database\Factories;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StaffFactory extends Factory
{
    protected $model = Staff::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'avatar' => $this->faker->imageUrl(200, 200, 'people'), // Ảnh ngẫu nhiên
            'birthday' => $this->faker->date('Y-m-d', '2002-01-01'), // Ngày sinh ngẫu nhiên trước 2002
            'phone' => $this->faker->unique()->phoneNumber(),
            'address' => $this->faker->address(),
            'email' => $this->faker->unique()->safeEmail(),
            'status' => $this->faker->randomElement(['active', 'inactive', 'on_leave']),
            'role' => $this->faker->randomElement(['admin', 'manager', 'employee']),
            'salary' => $this->faker->randomFloat(2, 500, 5000), // Lương từ 500 đến 5000
            'date_hired' => $this->faker->date(),
            'insurance_number' => $this->faker->unique()->numerify('INS###-###-###'),
            'contract_type' => $this->faker->randomElement(['full-time', 'part-time', 'contract']),
            'contract_start' => $this->faker->date(),
            'contract_end' => $this->faker->optional()->date(), // Có thể null
            'notes' => $this->faker->optional()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
