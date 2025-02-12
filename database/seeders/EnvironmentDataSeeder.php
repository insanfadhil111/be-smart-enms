<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\EnvironmentData;
use Illuminate\Database\Seeder;

class EnvironmentDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Start date: 3 Days ago
        $startDate = Carbon::now()->subDays(3);

        // Number of data points
        $totalDataPoints = 1000; // Adjust this number as needed

        for ($i = 0; $i < $totalDataPoints; $i++) {
            EnvironmentData::factory()->create([
                'created_at' => $startDate->copy(),
                'updated_at' => $startDate->copy(),
            ]);

            // Increment the date by 5 minutes for each iteration
            $startDate->addMinutes(5);
        }
    }
}
