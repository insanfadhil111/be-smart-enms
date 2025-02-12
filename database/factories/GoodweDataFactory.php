<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GoodweData>
 */
class GoodweDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'P' => $this->faker->randomFloat(2, 0, 100), // Nilai P antara 0 dan 100 dengan 2 desimal
            'today_generation' => $this->faker->randomFloat(2, 1000, 2000), // Nilai today_generation antara 0 dan 100 dengan 2 desimal
            'total_generation' => $this->faker->randomFloat(2, 10000, 20000), // Nilai total_generation antara 0 dan 100 dengan 2 desimal
            'today_income' => $this->faker->randomFloat(2, 1000, 2000), // Nilai today_income antara 0 dan 100 dengan 2 desimal
            'total_income' => $this->faker->randomFloat(2, 10000, 20000), // Nilai total_income antara 0 dan 100 dengan 2 desimal
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
