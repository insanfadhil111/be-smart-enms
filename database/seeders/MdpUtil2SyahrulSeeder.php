<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\MdpUtil2Syahrul;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MdpUtil2SyahrulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert data for yesterday
        MdpUtil2Syahrul::create([
            'tanggal' => Carbon::yesterday()->format('d/m/y'), // Yesterday's date
            'jam' => Carbon::now()->subDay()->format('H:i'),   // Current time minus 1 day
            'data_Ua3' => 229.4,
            'data_Ub3' => 230.3,
            'data_Uc3' => 231.2,
            'data_Ia3' => 14.8,
            'data_Ib3' => 15.7,
            'data_Ic3' => 16.9,
            'data_It3' => 47.4,
            'data_Pa3' => 340.1,
            'data_Pb3' => 350.2,
            'data_Pc3' => 360.3,
            'data_Pt3' => 1050.6,
            'data_Qa3' => 44.0,
            'data_Qb3' => 45.1,
            'data_Qc3' => 46.2,
            'data_Qt3' => 135.3,
            'data_Sa3' => 375.0,
            'data_Sb3' => 385.1,
            'data_Sc3' => 395.2,
            'data_St3' => 1155.3,
            'data_Pfa3' => 0.94,
            'data_Pfb3' => 0.95,
            'data_Pfc3' => 0.96,
            'data_Pft3' => 0.95,
            'data_Hz3' => 50.0,
            'data_pkWh3' => 115.8,
            'data_nkWh3' => 57.9,
            'data_pkVarh3' => 29.2,
            'data_nkVarh3' => 14.6,
            'status' => 'OK',
        ]);
        // Insert data for today
        MdpUtil2Syahrul::create([
            'tanggal' => Carbon::now()->format('d/m/y'), // Today's date
            'jam' => Carbon::now()->format('H:i'),       // Current time
            'data_Ua3' => 222,
            'data_Ub3' => 231.5,
            'data_Uc3' => 232.5,
            'data_Ia3' => 15.2,
            'data_Ib3' => 16.3,
            'data_Ic3' => 17.4,
            'data_It3' => 48.9,
            'data_Pa3' => 350.7,
            'data_Pb3' => 360.8,
            'data_Pc3' => 370.9,
            'data_Pt3' => 1082.4,
            'data_Qa3' => 45.2,
            'data_Qb3' => 46.3,
            'data_Qc3' => 47.4,
            'data_Qt3' => 138.9,
            'data_Sa3' => 385.5,
            'data_Sb3' => 395.6,
            'data_Sc3' => 405.7,
            'data_St3' => 1186.8,
            'data_Pfa3' => 0.95,
            'data_Pfb3' => 0.96,
            'data_Pfc3' => 0.97,
            'data_Pft3' => 0.96,
            'data_Hz3' => 50.1,
            'data_pkWh3' => 120.4,
            'data_nkWh3' => 60.2,
            'data_pkVarh3' => 30.1,
            'data_nkVarh3' => 15.0,
            'status' => 'OK',
        ]);
    }
}
