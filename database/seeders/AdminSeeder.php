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
                'username' => 'made',
                'email' => 'andika2003.ap31@gmail.com',
                'password' => Hash::make('adminmade'), // Ganti dengan password yang diinginkan
            ],

        ]);
    }
}
