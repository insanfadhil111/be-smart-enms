<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EnergyPredictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        // Start date is today
        $startDate = Carbon::today();

        for ($i = 0; $i < 14; $i++) {
            $data[] = [
                'date' => $startDate->copy()->addDays($i)->format('Y-m-d'),
                'prediction' => rand(200, 800), // Random prediction between 1000 and 5000
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('energy_predicts')->insert($data);
    }
}
