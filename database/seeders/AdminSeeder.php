<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->insert([
            [
                'username' => 'admin1',
                'password' => Hash::make('admin1kece'), // Ganti dengan password yang diinginkan
            ],
            [
                'username' => 'admin2',
                'password' => Hash::make('admin2kece'), // Ganti dengan password yang diinginkan
            ],

        ]);
    }
}
