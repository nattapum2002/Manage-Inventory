<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 30; $i++) {
            DB::table('customer_order')->insert([
                'order_id' => $i,
                'customer_id' => $i,
                'team_id' => $i,
                'note' => 'note' . $i,
                'status' => rand(0, 1),
            ]);
        }
    }
}
