<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('consultive_rooms')->insert([
            'name' => 'Escazú',
            'address' => 'Centro Médico Momentum , enfrente de Multiplaza Escazú. Piso 7 , consultorio 72.',
            'phone' => '2253-3773',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('consultive_rooms')->insert([
            'name' => 'Calle Blancos',
            'address' => 'Centro Médico Centauro , costado SUR de la Clinica Católica, Piso 3, consultorio 322',
            'phone' => '2253-3773',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
