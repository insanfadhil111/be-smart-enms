<?php

namespace Database\Seeders;

use App\Models\IkeStandar;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IkeStandarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $values = [];
        for ($i = 0; $i < 14; $i++) {
            $totalEnergy = rand(300, 2000);
            $createdAt = Carbon::now()->subMonths(14 - $i)->endOfMonth()->setHour(23)->setMinute(50)->setSecond(0);
            $updatedAt = $createdAt;
            $values[] = ['total_energy' => $totalEnergy, 'created_at' => $createdAt, 'updated_at' => $updatedAt];
        };


        foreach ($values as $value) {
            IkeStandar::create($value);
        }
    }
}
