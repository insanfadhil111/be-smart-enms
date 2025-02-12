<?php

namespace Database\Seeders;

use App\Models\Subdata;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubdataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subdata::create([
            'hargaKwh' => 1440,
            'co2eq' => 0.85,
            'hargaPdam' => 1575,
            'kwhAirPerMeterKubik' => 0.039,
            'trees_eq' => 2,
            'coal_eq' => 0.538,
            'decimal_sep' => ',',
            'thousand_sep' => '.',
        ]);
    }
}
