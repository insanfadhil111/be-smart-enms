<?php

namespace Database\Seeders;

use App\Models\Water;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WaterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Call both seeding functions
        $this->seedEachDay();
        $this->seedEveryFiveMinutes();
    }

    // 1. Seeding the database with a record for each day
    public function seedEachDay()
    {
        // Number of days you want to seed (let's say the last 30 days)
        $days = 1000;
        $volume = 0; //initial value

        $idDev = [119, 148];
        for ($i = 0; $i < $days - 1; $i++) {
            foreach ($idDev as $id_dev) {
                $debit = mt_rand(1, 4) / 100;
                $volume = $volume + $debit * 300; // Simulated volume value
                DB::table('waters')->insert([
                    'id_dev' => $id_dev,
                    'debit' => $debit,
                    'volume' => $volume,
                    'created_at' => Carbon::now()->subDays($days - $i + 7),
                    'updated_at' => Carbon::now()->subDays($days - $i + 7),
                ]);
            }
        }
    }

    // 2. Seeding the database with 500 data with timestamps every 15 minutes
    public function seedEveryFiveMinutes()
    {
        $berapaHari = 7;
        $totalRecords = ($berapaHari + 1) * 4 * 24; // 4 data per hour, 24 hours a day
        $startTime = Carbon::today()->subdays($berapaHari)->startOfDay();
        $volume = Water::latest()->first()->volume; // Initial volume
        $idDev = [119, 148];

        for ($i = 0; $i < $totalRecords; $i++) {
            foreach ($idDev as $id_dev) {
                $debit = mt_rand(1, 4) / 100;
                $volume += $debit * 300; // Incremental volume value
                DB::table('waters')->insert([
                    'id_dev' => $id_dev,
                    'debit' => $debit,
                    'volume' => $volume,
                    'created_at' => $startTime->copy()->addMinutes($i * 15),
                    'updated_at' => $startTime->copy()->addMinutes($i * 15),
                ]);
            }
        }
    }
}
