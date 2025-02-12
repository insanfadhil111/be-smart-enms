<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MppData;
use Carbon\Carbon;

class MppDataFactory extends Factory
{
    protected $model = MppData::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'P' => $this->faker->randomFloat(2, 0, 2000), // Nilai P antara 0 dan 100 dengan 2 desimal
            'gridVoltageR' => $this->faker->randomFloat(2, 0, 400),
            'gridpowerR' => $this->faker->randomFloat(2, 0, 2000),
            'gridFreqR' => $this->faker->randomFloat(2, 45, 65),
            'gridCurrR' => $this->faker->randomFloat(2, 0, 100),
            'ACOutVolR' => $this->faker->randomFloat(2, 0, 400),
            'ACOutPowR' => $this->faker->randomFloat(2, 0, 2000),
            'ACOutFreqR' => $this->faker->randomFloat(2, 45, 65),
            'ACOutCurrR' => $this->faker->randomFloat(2, 0, 100),
            'OUTLoadPerc' => $this->faker->randomFloat(2, 0, 100),
            'PVInPow1' => $this->faker->randomFloat(2, 0, 5000),
            'PVInPow2' => $this->faker->randomFloat(2, 0, 5000),
            'temp' => $this->faker->randomFloat(2, -10, 50),
            'devstatus' => $this->faker->numberBetween(0, 1),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
