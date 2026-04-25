<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@pos.com',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'phone' => '082255638447',
            ],
            [
                'name' => 'Kasir',
                'email' => 'kasir@pos.com',
                'role' => 'kasir',
                'password' => Hash::make('password'),
                'phone' => '082255638447',
            ],
            [
                'name' => 'Fariz',
                'email' => 'barber1@pos.com',
                'role' => 'barber',
                'password' => Hash::make('password'),
                'phone' => '082255638447',
            ],
            [
                'name' => 'Anshori',
                'email' => 'customer1@pos.com',
                'role' => 'customer',
                'password' => Hash::make('password'),
                'phone' => '082255638447',
            ],
        ]);
    }
}
