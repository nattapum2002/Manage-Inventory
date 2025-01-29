<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 30; $i++) {
            DB::table('product_stock')->insert([
                'product_id' => $i,
                'product_name' => 'product' . $i,
                'weight' => $i * 10,
                'amount' => $i * 10,
                'note' => 'note' . $i,
                'status' => rand(0, 1),
            ]);
        }
    }
}
