<?php

namespace Database\Factories;

use App\Models\MdpKwh;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MdpKwh>
 */
class MdpKwhFactory extends Factory
{
    public function definition(): array
    {
        static $kwh1, $kwh2, $kwh3;
        $lastData = MdpKwh::latest()->first();
        $kwh1 = $lastData ? $lastData->kwh_1 : 0;
        $kwh2 = $lastData ? $lastData->kwh_2 : 0;
        $kwh3 = $lastData ? $lastData->kwh_3 : 0;

        $increment_1 = $this->faker->numberBetween(1, 10);
        $increment_2 = $this->faker->numberBetween(1, 2);
        $kwh1 += $increment_1;
        $kwh2 += $increment_2;
        $kwh3 += $increment_2;
        return [
            'kwh_1' => $kwh1,
            'kwh_2' => $kwh2,
            'kwh_3' => $kwh3,
            'kwh_4' => 0,
            'kwh_5' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
