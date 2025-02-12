<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'username' => 'admin',
            'firstname' => 'Admin',
            'lastname' => 'Admin',
            'email' => 'admin@argon.com',
            'password' => bcrypt('secret'),
            'level' => 'admin'
        ]);
        DB::table('users')->insert([
            'username' => 'mario',
            'firstname' => 'Mario',
            'lastname' => 'Alfandi',
            'email' => 'mario@argon.com',
            'password' => bcrypt('secret'),
            'level' => 'user'
        ]);
    }
}
