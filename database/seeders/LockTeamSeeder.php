<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LockTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $team = ['A', 'B', 'C', 'D'];
        for ($i = 0; $i < 30; $i++) {
            DB::table('team')->insert([
                'team_id' => $i,
                'team_name' => $team[rand(0, 3)],
                'date' => '2022-01-01',
                'note' => 'note' . $i,
                'status' => rand(0, 1),
            ]);
        }
    }
}
