<?php

namespace Database\Factories;

use App\Models\MdpData;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MdpData>
 */
class MdpDataFactory extends Factory
{
    protected $model = MdpData::class;

    public function definition(): array
    {
        return [
            'id_kwh' => $this->faker->randomDigitNotNull,
            'Van' => $this->faker->randomFloat(2, 200, 240), // Example range for voltage
            'Vbn' => $this->faker->randomFloat(2, 200, 240),
            'Vcn' => $this->faker->randomFloat(2, 200, 240),
            'Ia' => $this->faker->randomFloat(2, 0, 100), // Example range for current
            'Ib' => $this->faker->randomFloat(2, 0, 100),
            'Ic' => $this->faker->randomFloat(2, 0, 100),
            'It' => $this->faker->randomFloat(2, 0, 300), // Example range for total current
            'Pa' => $this->faker->randomFloat(2, 0, 5000), // Example range for power
            'Pb' => $this->faker->randomFloat(2, 0, 5000),
            'Pc' => $this->faker->randomFloat(2, 0, 5000),
            'Pt' => $this->faker->randomFloat(2, 0, 15000), // Example range for total power
            'Qa' => $this->faker->randomFloat(2, 0, 5000), // Example range for reactive power
            'Qb' => $this->faker->randomFloat(2, 0, 5000),
            'Qc' => $this->faker->randomFloat(2, 0, 5000),
            'Qt' => $this->faker->randomFloat(2, 0, 15000), // Example range for total reactive power
            'pf' => $this->faker->randomFloat(2, 0.5, 1.0), // Example range for power factor
            'f' => $this->faker->randomFloat(2, 49.0, 51.0), // Example range for frequency
            'created_at' => null, // Will be set in the seeder
            'updated_at' => null, // Will be set in the seeder
        ];
    }
}
