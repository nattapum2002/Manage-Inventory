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
            DB::table('receipt_product')->insert([
                'product_slip_id' => $i,
                'product_slip_number' => $i,
                'department' => 'department' . $i,
                'store_date' => '2022-01-01',
                'store_time' => '00:00:00',
                'product_checker' => rand(0, 1),
                'domestic_checker' => rand(0, 1),
                'shift_id' => $i,
                'note' => 'note' . $i,
                'status' => rand(0, 1),
            ]);
        }
    }
}
