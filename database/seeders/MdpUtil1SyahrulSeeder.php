<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\MdpUtil1Syahrul;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MdpUtil1SyahrulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert data for yesterday
        MdpUtil1Syahrul::create([
            'tanggal' => Carbon::yesterday()->format('d/m/y'), // Yesterday's date
            'jam' => Carbon::now()->subDay()->format('H:i'),   // Current time minus 1 day
            'data_Ua2' => 229.4,
            'data_Ub2' => 230.3,
            'data_Uc2' => 231.2,
            'data_Ia2' => 14.8,
            'data_Ib2' => 15.7,
            'data_Ic2' => 16.9,
            'data_It2' => 47.4,
            'data_Pa2' => 340.1,
            'data_Pb2' => 350.2,
            'data_Pc2' => 360.3,
            'data_Pt2' => 1050.6,
            'data_Qa2' => 44.0,
            'data_Qb2' => 45.1,
            'data_Qc2' => 46.2,
            'data_Qt2' => 135.3,
            'data_Sa2' => 375.0,
            'data_Sb2' => 385.1,
            'data_Sc2' => 395.2,
            'data_St2' => 1155.3,
            'data_Pfa2' => 0.94,
            'data_Pfb2' => 0.95,
            'data_Pfc2' => 0.96,
            'data_Pft2' => 0.95,
            'data_Hz2' => 50.0,
            'data_pkWh2' => 115.8,
            'data_nkWh2' => 57.9,
            'data_pkVarh2' => 29.2,
            'data_nkVarh2' => 14.6,
            'status' => 'OK',
        ]);
        // Insert data for today
        MdpUtil1Syahrul::create([
            'tanggal' => Carbon::now()->format('d/m/y'), // Today's date
            'jam' => Carbon::now()->format('H:i'),       // Current time
            'data_Ua2' => 221,
            'data_Ub2' => 231.5,
            'data_Uc2' => 232.5,
            'data_Ia2' => 15.2,
            'data_Ib2' => 16.3,
            'data_Ic2' => 17.4,
            'data_It2' => 48.9,
            'data_Pa2' => 350.7,
            'data_Pb2' => 360.8,
            'data_Pc2' => 370.9,
            'data_Pt2' => 1082.4,
            'data_Qa2' => 45.2,
            'data_Qb2' => 46.3,
            'data_Qc2' => 47.4,
            'data_Qt2' => 138.9,
            'data_Sa2' => 385.5,
            'data_Sb2' => 395.6,
            'data_Sc2' => 405.7,
            'data_St2' => 1186.8,
            'data_Pfa2' => 0.95,
            'data_Pfb2' => 0.96,
            'data_Pfc2' => 0.97,
            'data_Pft2' => 0.96,
            'data_Hz2' => 50.1,
            'data_pkWh2' => 120.4,
            'data_nkWh2' => 60.2,
            'data_pkVarh2' => 30.1,
            'data_nkVarh2' => 15.0,
            'status' => 'OK',
        ]);
    }
}
