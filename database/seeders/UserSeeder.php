<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_type = ['Admin', 'User', 'Manager'];
        $position = ['Admin', 'User', 'Manager'];
        for ($i = 0; $i < 30; $i++) {
            DB::table('users')->insert([
                'user_id' => $i,
                'name' => 'user' . $i,
                'surname' => 'surname' . $i,
                'position' => $position[rand(0, 2)],
                'start_date' => '2022-01-01',
                'user_type' => $user_type[rand(0, 2)],
                // 'email' => 'user' . $i . '@email.com',
                'password' => Hash::make('password'),
                'note' => 'note' . $i,
                'status' => rand(0, 1),
            ]);
        }
    }
}
