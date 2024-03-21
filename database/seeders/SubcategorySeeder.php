<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subcategory')->insert([
            'description' => 'Nunca',
            'category_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => 'Ocasionalmente',
            'category_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => 'Socialmente',
            'category_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => 'Semanalmente',
            'category_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => 'Diario',
            'category_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => 'No fumador',
            'category_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => 'Exfumador',
            'category_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => 'Fumador ocasional',
            'category_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => 'Fumador moderado',
            'category_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => 'Fumador empedernido',
            'category_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => 'Menos de 1 litro',
            'category_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => '1-2 litros',
            'category_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => '2-3 litros',
            'category_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => 'Más de 3 litros',
            'category_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => 'Bajo',
            'category_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => 'Moderado',
            'category_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => 'Alto',
            'category_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => 'Muy alto',
            'category_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => 'Menos de 5 horas',
            'category_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => '5-6 horas',
            'category_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => '6-7 horas',
            'category_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => '7-8 horas',
            'category_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subcategory')->insert([
            'description' => 'Más de 8 horas',
            'category_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
