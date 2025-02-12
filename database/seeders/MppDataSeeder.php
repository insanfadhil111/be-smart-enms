<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MppData;
use Carbon\Carbon;

class MppDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $berapaHari = 30;
        $startDate = Carbon::today()->subdays($berapaHari)->startOfDay();
        $totalDataPoints = ($berapaHari + 1) * 12 * 24; // 12 data per hour, 24 hours a day

        for ($i = 0; $i < $totalDataPoints; $i++) {
            MppData::factory()->create([
                'created_at' => $startDate->copy(),
                'updated_at' => $startDate->copy(),
            ]);

            // Increment the date by 5 minutes for each iteration
            $startDate->addMinutes(5);
        }
    }
}
