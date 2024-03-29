<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(CategorySeeder::class);
        $this->call(SubcategorySeeder::class);
        $this->call(RolSeeder::class);
        $this->call(RoomSeeder::class);
        $this->call(PatienceSeeder::class);
    }
}
