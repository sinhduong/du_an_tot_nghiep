<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Review::factory(10)->create();
    }
}
