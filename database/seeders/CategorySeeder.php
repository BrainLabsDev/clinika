<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('category')->insert([
            'name' => 'alcohol_consumption',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('category')->insert([
            'name' => 'smoke',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('category')->insert([
            'name' => 'water_consumption',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('category')->insert([
            'name' => 'stress',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('category')->insert([
            'name' => 'hours_of_sleep',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
