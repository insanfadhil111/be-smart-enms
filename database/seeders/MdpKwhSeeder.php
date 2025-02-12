<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\MdpKwh;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MdpKwhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->injectPLN();
        $this->seedEveryHour();
    }
    public function injectPLN()
    {
        /* Inject Data Bulanan PLN */
        $path = database_path('seeders/kwh_inject.sql');
        DB::unprepared(File::get($path));
    }

    public function seedEveryHour()
    {
        $lastDataFromPLN = MdpKwh::latest()->first()->created_at; // Initial value
        $startTime = $lastDataFromPLN->copy()->addDay();
        $currentTime = Carbon::now();
        $totalRecords = $startTime->diffInHours($currentTime);

        MdpKwh::create([
            'kwh_1' => 0,
            'kwh_2' => 0,
            'kwh_3' => 0,
            'kwh_4' => 0,
            'kwh_5' => 0,
            'created_at' => $startTime->copy(),
            'updated_at' => $startTime->copy(),
        ]);

        for ($i = 0; $i < $totalRecords; $i++) {
            MdpKwh::factory()->create([
                'created_at' => $startTime->copy(),
                'updated_at' => $startTime->copy(),
            ]);
            $startTime->addHour(); // Interval
        }
    }
}
