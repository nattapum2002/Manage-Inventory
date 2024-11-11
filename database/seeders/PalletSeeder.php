<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bag_color = ['rad', 'green', 'blue', 'yellow'];
        for ($i = 0; $i < 30; $i++) {
            DB::table('pallet')->insert([
                'pallet_id' => $i,
                'pallet_no' => $i,
                'room' => 'room' . $i,
                'order_id' => $i,
                'note' => 'note' . $i,
                'status' => rand(0, 1),
            ]);
        }
    }
}
