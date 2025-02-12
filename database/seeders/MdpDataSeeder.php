<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MdpData;
use Carbon\Carbon;

class MdpDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Start date: 3 days ago
        $startDate = Carbon::now()->subDays(3);

        // Number of data points
        $totalDataPoints = 1000; // Adjust this number as needed

        for ($i = 0; $i < $totalDataPoints; $i++) {
            // Generate id_kwh in a repeating sequence from 1 to 5
            $id_kwh = ($i % 5) + 1;

            MdpData::factory()->create([
                'id_kwh' => $id_kwh,
                'created_at' => $startDate->copy(),
                'updated_at' => $startDate->copy(),
            ]);

            // Increment the date by 5 minutes for each iteration
            $startDate->addMinutes(5);
        }
    }
}
