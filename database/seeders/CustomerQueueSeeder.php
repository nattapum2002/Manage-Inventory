<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerQueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 30; $i++) {
            DB::table('customer_queue')->insert([
                'queue_id' => $i,
                'order_id' => $i,
                'queue' => $i,
                'time_queue' => '00:00:00',
                'recive_time' => '00:00:00',
                'recive_status' => rand(0, 1),
            ]);
        }
    }
}