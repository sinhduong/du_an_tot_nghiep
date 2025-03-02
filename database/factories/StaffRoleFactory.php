<?php

namespace Database\Factories;

use App\Models\StaffRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StaffRole>
 */
class StaffRoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = StaffRole::class;

    public function definition()
    {
        // return [
        //     'name' => $this->faker->unique()->randomElement(['Admin', 'Quản lý', 'Nhân viên', 'Lễ tân', 'Bảo vệ']),
        //     'permissions' => json_encode([
        //         'view' => $this->faker->boolean(),
        //         'edit' => $this->faker->boolean(),
        //         'delete' => $this->faker->boolean(),
        //         'create' => $this->faker->boolean(),
        //     ]),
        // ];
    }
}
