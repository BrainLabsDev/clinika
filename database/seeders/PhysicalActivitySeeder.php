<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhysicalActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('physical_activities')->insert([
            'name' => 'Ligero',
            'sku' => 'ligero',
            'description' => 'actividad ligera o ejercicio 1-3 días a la semana',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('physical_activities')->insert([
            'name' => 'Moderado',
            'sku' => 'moderado',
            'description' => 'ejercicio moderado o deportes 3-5 días a la semana',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('physical_activities')->insert([
            'name' => 'Activo',
            'sku' => 'activo',
            'description' => 'ejercicio intenso o deportes 6-7 días a la semana',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('physical_activities')->insert([
            'name' => 'Muy activo',
            'sku' => 'muy-activo',
            'description' => 'ejercicio muy intenso o deportes y actividad física laboral',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
