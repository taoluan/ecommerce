<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category1 = DB::table('product_categories')->insertGetId([
            'name' => 'Electronics',
            'store_id' => 1
        ]);
        $category2 = DB::table('product_categories')->insertGetId([
            'name' => 'Electronics',
            'store_id' => 2
        ]);

        // Tạo dữ liệu mẫu cho products
        DB::table('products')->insert([
            [
                'store_id' => 1,
                'category_id' => $category1,
                'name' => 'Product 1',
                'description' => 'This is product 1',
                'price' => 100,
                'active' => 1,
            ],
            [
                'store_id' => 1,
                'category_id' => $category1,
                'name' => 'Product 2',
                'description' => 'This is product 2',
                'price' => 150,
                'active' => 1,
            ],
            [
                'store_id' => 2,
                'category_id' => $category2,
                'name' => 'Product 1',
                'description' => 'This is product 1',
                'price' => 100,
                'active' => 1,
            ],
            [
                'store_id' => 2,
                'category_id' => $category2,
                'name' => 'Product 2',
                'description' => 'This is product 2',
                'price' => 150,
                'active' => 1,
            ],
        ]);
    }
}
