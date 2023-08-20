<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stores')->insert([
            [
                'user_id' => 1,
                'name' => 'Store 1',
                'address' => '123 Main Street',
                'active' => 1,
            ],
            [
                'user_id' => 2,
                'name' => 'Store 2',
                'address' => '456 Elm Street',
                'active' => 1,
            ],
        ]);

    }
}
