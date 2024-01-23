<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObjectivesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('objectives')->insert([
            'name' => 'Pérdida de peso',
            'sku' => 'perdida-de-peso',
            'description' => 'enfocada en reducir la ingesta calórica y fomentar una alimentación equilibrada para perder peso',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('objectives')->insert([
            'name' => 'Mantenimiento de peso',
            'sku' => 'mantenimiento-de-peso',
            'description' => 'mantener el peso actual con una dieta equilibrada y adecuada para el nivel de actividad física',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('objectives')->insert([
            'name' => 'Ganancia de peso',
            'sku' => 'ganancia-de-peso',
            'description' => 'aumentar la ingesta calórica y enfocarse en nutrientes que favorezcan el crecimiento muscular y la ganancia de peso',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('objectives')->insert([
            'name' => 'Rendimiento deportivo',
            'sku' => 'rendimiento-deportivo',
            'description' => 'dieta orientada a mejorar el rendimiento en deportes o actividades físicas específicas, con énfasis en la proporción adecuada de macronutrientes',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
