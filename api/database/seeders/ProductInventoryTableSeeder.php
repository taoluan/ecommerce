<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductInventoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = DB::table('products')->pluck('id');

        foreach ($products as $productId) {
            DB::table('product_inventory')->insert([
                'product_id' => $productId,
                'quantity' => rand(1, 100),
            ]);
        }
    }
}
