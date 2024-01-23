<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Suscription;
use App\Models\MedicalRecord;

class PatienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'first_lastname' => 'App',
            'second_lastname' => 'App',
            'sex' => 'M',
            'email' => 'admin@gmail.com',
            'phone' => '8888-8888',
            'birthday' => '1992-06-24',
            'rol' => 'Usuario',
            'nutricionist_id' => null,
            'password' => Hash::make('admin01'),
            'room_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Nutricionista 1',
            'first_lastname' => 'Test',
            'second_lastname' => 'Test',
            'sex' => 'F',
            'email' => 'nutri1@gmail.com',
            'phone' => '8888-8888',
            'birthday' => '1992-06-24',
            'rol' => 'Nutricionista',
            'nutricionist_id' => null,
            'password' => Hash::make('nutri01'),
            'room_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Nutricionista 2',
            'first_lastname' => 'Test',
            'second_lastname' => 'Test',
            'sex' => 'F',
            'email' => 'nutri2@gmail.com',
            'phone' => '8888-8888',
            'birthday' => '1992-06-24',
            'rol' => 'Nutricionista',
            'nutricionist_id' => null,
            'password' => Hash::make('nutri02'),
            'room_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Pedro',
            'first_lastname' => 'Sanchez',
            'second_lastname' => 'Guerrero',
            'sex' => 'M',
            'email' => 'juan_alucard@hotmail.com',
            'phone' => '8888-8888',
            'birthday' => '1992-06-24',
            'rol' => 'Usuario',
            'nutricionist_id' => 2,
            'password' => Hash::make('user01'),
            'room_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Usuario 2',
            'first_lastname' => 'Test',
            'second_lastname' => 'Test',
            'sex' => 'F',
            'email' => 'user2@gmail.com',
            'phone' => '8888-8888',
            'birthday' => '1992-06-24',
            'rol' => 'Usuario',
            'nutricionist_id' => 3,
            'password' => Hash::make('user02'),
            'room_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Usuario 3',
            'first_lastname' => 'Test',
            'second_lastname' => 'Test',
            'sex' => 'M',
            'email' => 'user3@gmail.com',
            'phone' => '8888-8888',
            'birthday' => '1992-06-24',
            'rol' => 'Usuario',
            'nutricionist_id' => 2,
            'password' => Hash::make('user03'),
            'room_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = User::where('email', 'nutri2@gmail.com')->first();
        $user->assignRole('Nutricionista');

        $user = User::where('email', 'nutri1@gmail.com')->first();
        $user->assignRole('Nutricionista');

        $user = User::where('email', 'user3@gmail.com')->first(); // Suscripcion vencida
        $user->assignRole('Usuario');
        $suscripcion = new Suscription();
        $suscripcion->start_date = date('Y-m-d',strtotime('-2 month'));
        $suscripcion->end_date = date('Y-m-d', strtotime('-1 month'));
        $suscripcion->user_id = $user->id;
        $suscripcion->save();
        $medical_record = new MedicalRecord();
        $medical_record->alergies = json_encode(['Lactosa', 'Gluten', 'Cacahuate']);
        $medical_record->health_conditions = json_encode(['Diabetes', 'Hipertension']);
        $medical_record->physical_activity_id = 3;
        $medical_record->objective_id = 4;
        $medical_record->user_id = $user->id;
        $medical_record->save();

        $user = User::where('email', 'user2@gmail.com')->first();
        $user->assignRole('Usuario');
        $suscripcion = new Suscription();
        $suscripcion->start_date = date('Y-m-d');
        $suscripcion->end_date = date('Y-m-d', strtotime('+1 month'));
        $suscripcion->user_id = $user->id;
        $suscripcion->save();

        $user = User::where('email', 'juan_alucard@hotmail.com')->first();
        $user->assignRole('Usuario');
        $suscripcion = new Suscription();
        $suscripcion->start_date = date('Y-m-d');
        $suscripcion->end_date = date('Y-m-d', strtotime('+1 month'));
        $suscripcion->user_id = $user->id;
        $suscripcion->save();
        $medical_record = new MedicalRecord();
        $medical_record->alergies = json_encode(['Lactosa', 'Gluten']);
        $medical_record->health_conditions = json_encode(['Diabetes']);
        $medical_record->physical_activity_id = 1;
        $medical_record->objective_id = 2;
        $medical_record->user_id = $user->id;
        $medical_record->save();


        $user = User::where('email', 'admin@gmail.com')->first();
        $user->assignRole('SuperAdmin');
    }
}
