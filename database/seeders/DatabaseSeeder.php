<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\WaterSeeder;
use Database\Seeders\MdpKwhSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\MdpDataSeeder;
use Database\Seeders\MppDataSeeder;
use Database\Seeders\PvSubdataSeeder;
use Database\Seeders\GoodweDataSeeder;
use Database\Seeders\IkeStandarSeeder;
use Database\Seeders\MdpControlSeeder;
use Database\Seeders\EnergyPredictSeeder;
use Database\Seeders\EnvironmentDataSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            EnergyPredictSeeder::class,
            EnvironmentDataSeeder::class,
            GoodweDataSeeder::class,
            IkeStandarSeeder::class,
            MdpControlSeeder::class,
            MdpDataSeeder::class,
            MdpKwhSeeder::class,
            MppDataSeeder::class,
            PvSubdataSeeder::class,
            UserSeeder::class,
            WaterSeeder::class,
            SubdataSeeder::class,
        ]);
    }
}
