<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MdpControl;

class MdpControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $devices = [
            ['device' => 'SDP AC 1', 'status' => 0],
            ['device' => 'SDP AC 2', 'status' => 0],
            ['device' => 'Utilitas 1', 'status' => 0],
            ['device' => 'Utilitas 2', 'status' => 0],
        ];

        foreach ($devices as $device) {
            MdpControl::create($device);
        }
    }
}
