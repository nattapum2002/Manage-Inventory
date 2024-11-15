<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerOrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $color = ['Rad', 'Green', 'Blue', 'Yellow'];
        for ($i = 0; $i < 30; $i++) {
            DB::table('customer_order_detail')->insert([
                'product_id' => $i,
                'product_name' => 'product' . $i,
                'amount_order' => $i,
                'amount_paid' => $i,
                'bag_color' => $color[rand(0, 3)],
                'order_id' => $i,
                'note' => 'note' . $i,
                'status' => rand(0, 1),
            ]);
        }
    }
}
