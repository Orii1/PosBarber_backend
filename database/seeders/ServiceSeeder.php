<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'name' => 'Potong Rambut',
                'price' => 60000,
            ],
            [
                'name' => 'Cuci Rambut',
                'price' => 15000,
            ],
        ]);
    }
}
