<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shift_name = ['A', 'B', 'C', 'D'];
        for ($i = 0; $i < 30; $i++) {
            DB::table('shift')->insert([
                'shift_id' => $i,
                'shift_name' => $shift_name[rand(0, 3)],
                'start_shift' => '00:00:00',
                'end_shift' => '00:00:00',
                'date' => '2022-01-01',
                'note' => 'note' . $i,
                'status' => rand(0, 1),
            ]);
        }
    }
}
