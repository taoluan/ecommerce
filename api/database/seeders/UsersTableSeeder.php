<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'email' => 'user1@gmail.com',
                'password' => Hash::make('123456'),
                'first_name' => 'John',
                'last_name' => 'Doe',
            ],
            [
                'email' => 'user2@gmail.com',
                'password' => Hash::make('123456'),
                'first_name' => 'Jane',
                'last_name' => 'Smith',
            ],

        ]);
    }
}
