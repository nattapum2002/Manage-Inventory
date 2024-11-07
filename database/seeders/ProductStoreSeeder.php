<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 30; $i++) {
            DB::table('product_store')->insert([
                'product_slip_id' => $i,
                'product_slip_number' => $i,
                'product_id' => $i,
                'department' => 'department' . $i,
                'weight' => $i * 10,
                'amount' => $i * 10,
                'comment' => 'comment' . $i,
                'store_date' => '2022-01-01',
                'store_time' => '00:00:00',
                'check_status' => rand(0, 1),
                'product_checker' => $i,
                'domestic_checker' => $i,
            ]);
        }
    }
}