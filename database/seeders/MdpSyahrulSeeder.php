<?php

namespace Database\Seeders;

use App\Models\MdpSyahrul;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MdpSyahrulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert data for yesterday
        MdpSyahrul::create([
            'tanggal' => Carbon::yesterday()->format('d/m/y'), // Yesterday's date
            'jam' => Carbon::now()->subDay()->format('H:i'),   // Current time minus 1 day
            'data_Ua1' => 229.4,
            'data_Ub1' => 230.3,
            'data_Uc1' => 231.2,
            'data_Ia1' => 14.8,
            'data_Ib1' => 15.7,
            'data_Ic1' => 16.9,
            'data_It1' => 47.4,
            'data_Pa1' => 340.1,
            'data_Pb1' => 350.2,
            'data_Pc1' => 360.3,
            'data_Pt1' => 1050.6,
            'data_Qa1' => 44.0,
            'data_Qb1' => 45.1,
            'data_Qc1' => 46.2,
            'data_Qt1' => 135.3,
            'data_Sa1' => 375.0,
            'data_Sb1' => 385.1,
            'data_Sc1' => 395.2,
            'data_St1' => 1155.3,
            'data_Pfa1' => 0.94,
            'data_Pfb1' => 0.95,
            'data_Pfc1' => 0.96,
            'data_Pft1' => 0.95,
            'data_Hz1' => 50.0,
            'data_pkWh1' => 115.8,
            'data_nkWh1' => 57.9,
            'data_pkVarh1' => 29.2,
            'data_nkVarh1' => 14.6,
            'status' => 'OK',
        ]);
        // Insert data for today
        MdpSyahrul::create([
            'tanggal' => Carbon::now()->format('d/m/y'), // Today's date
            'jam' => Carbon::now()->format('H:i'),       // Current time
            'data_Ua1' => 220,
            'data_Ub1' => 231.5,
            'data_Uc1' => 232.5,
            'data_Ia1' => 15.2,
            'data_Ib1' => 16.3,
            'data_Ic1' => 17.4,
            'data_It1' => 48.9,
            'data_Pa1' => 350.7,
            'data_Pb1' => 360.8,
            'data_Pc1' => 370.9,
            'data_Pt1' => 1082.4,
            'data_Qa1' => 45.2,
            'data_Qb1' => 46.3,
            'data_Qc1' => 47.4,
            'data_Qt1' => 138.9,
            'data_Sa1' => 385.5,
            'data_Sb1' => 395.6,
            'data_Sc1' => 405.7,
            'data_St1' => 1186.8,
            'data_Pfa1' => 0.95,
            'data_Pfb1' => 0.96,
            'data_Pfc1' => 0.97,
            'data_Pft1' => 0.96,
            'data_Hz1' => 50.1,
            'data_pkWh1' => 120.4,
            'data_nkWh1' => 60.2,
            'data_pkVarh1' => 30.1,
            'data_nkVarh1' => 15.0,
            'status' => 'OK',
        ]);
    }
}
