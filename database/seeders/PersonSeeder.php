<?php

namespace Database\Seeders;


use App\Models\Person;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Person::create([
            'letra_cedula' => 'V',
            'cedula' => 0,
            'primer_nombre' => 'Super',
            'primer_apellido' => 'Admin',
            'id_genero' => 1,
            'fecha_nacimiento' => '1999-01-01',
            'id_estado_nacimiento' => 1070000,
            'id_municipio_nacimiento' => 1080000,
            'id_pais_nacimiento' => 1060223
        ]);
    }
}
