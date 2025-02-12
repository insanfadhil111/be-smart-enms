<?php

namespace Database\Seeders;

use App\Models\PvSubdata;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PvSubdataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PvSubdata::create([
            'profit' => 1444.7,
            'co2_eq' => 0.85,
            'trees_eq' => 2,
            'coal_eq' => 0.538,
        ]);
    }
}
