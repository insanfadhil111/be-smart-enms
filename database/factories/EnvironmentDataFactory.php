<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EnvirontmentData>
 */
class EnvironmentDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'temperature' => $this->faker->randomFloat(2, -10, 50), // Temperature between -10 and 50 degrees
            'humidity' => $this->faker->randomFloat(2, 0, 100),     // Humidity percentage between 0 and 100
            'light_intensity' => $this->faker->randomNumber(4), // Light intensity (arbitrary unit)
            'air_pressure' => $this->faker->randomnumber(2, 950, 1050), // Air pressure between 950 and 1050 hPa
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
