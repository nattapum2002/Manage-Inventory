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
                'order_id' => $i,
                'product_id' => $i,
                'order_amount' => $i * 10,
                'send_amount' => $i * 10,
                'bag_color' => $bag_color[rand(0, 3)],
                'room' => 'room' . $i,
                'pack_start_time' => '00:00:00',
                'pack_end_time' => '00:00:00',
                'checker_id' => $i,
                'shift_id' => $i,
            ]);
        }
    }
}