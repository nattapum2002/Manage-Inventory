<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shift = DB::table('work_shift')->select('shift_id')->get();
        $user = DB::table('users')->select('user_id')->get();
        for ($i = 0; $i < 30; $i++) {
            DB::table('shift_users')->insert([
                'shift_id' => $shift[$i]->shift_id,
                'user_id' => $user[$i]->user_id,
            ]);
        }
    }
}