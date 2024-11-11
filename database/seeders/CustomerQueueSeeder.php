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
                'no' => $i,
                'queue_time' => '00:00:00',
                'queue_no' => $i,
                'entry_time' => '00:00:00',
                'release_time' => '00:00:00',
                'customer_id' => $i,
                'note' => 'note' . $i,
                'status' => rand(0, 1),
            ]);
        }
    }
}
