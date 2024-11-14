<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grade = ['A', 'B', 'C', 'D'];
        for ($i = 0; $i < 30; $i++) {
            DB::table('customer')->insert([
                'customer_id' => $i,
                'customer_name' => 'customer' . $i,
                'customer_grade' => $grade[rand(0, 3)],
                'note' => 'note' . $i,
                'status' => rand(0, 1),
            ]);
        }
    }
}
