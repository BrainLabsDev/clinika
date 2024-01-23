<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaypalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('paypal_catalogo')->insert([
            'sku' => Str::slug('1 mes', '-'),
            'nombre' => '1 mes',
            'representacion_numerica' => 1,
            'precio' => null,
            'descuento' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('paypal_catalogo')->insert([
            'sku' => Str::slug('3 meses', '-'),
            'nombre' => '3 meses',
            'representacion_numerica' => 3,
            'precio' => 30.00,
            'descuento' => 15.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('paypal_catalogo')->insert([
            'sku' => Str::slug('6 meses', '-'),
            'nombre' => '6 meses',
            'representacion_numerica' => 6,
            'precio' => 60.00,
            'descuento' => 30.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('paypal_catalogo')->insert([
            'sku' => Str::slug('1 año', '-'),
            'nombre' => '1 año',
            'representacion_numerica' => 12,
            'precio' => 120.00,
            'descuento' => 50.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
