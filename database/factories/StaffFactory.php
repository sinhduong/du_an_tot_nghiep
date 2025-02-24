<?php

namespace Database\Factories;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StaffFactory extends Factory
{
    protected $model = Staff::class;

    public function definition()
    {
        return [
            'staffs_code' => 'NV' . str_pad($this->faker->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'name' => $this->faker->name(),
            'avatar' => $this->faker->imageUrl(200, 200, 'people'),
            'birthday' => $this->faker->date(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'email' => $this->faker->unique()->safeEmail(),
            'status' => $this->faker->randomElement(['active', 'inactive', 'on_leave']),
            'salary' => $this->faker->randomFloat(2, 5000, 50000),
            'role' => $this->faker->randomElement(['Admin', 'Manager', 'Employee']),
            'date_hired' => $this->faker->date(),
            'insurance_number' => Str::random(10),
            'contract_type' => $this->faker->randomElement(['Full-time', 'Part-time', 'Contract']),
            'contract_start' => $this->faker->date(),
            'contract_end' => $this->faker->optional()->date(),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
