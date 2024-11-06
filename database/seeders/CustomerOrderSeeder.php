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
        for ($i = 0; $i < 10; $i++) {
            DB::table('customer_order')->insert([
                'order_id' => $i,
                'product_id' => $i,
                'customer_id' => $i,
                'order_amount' => $i * 10,
                'send_amount' => $i * 10,
                'date' => '2022-01-01',
                'packer_id' => $i,
            ]);
        }
    }
}