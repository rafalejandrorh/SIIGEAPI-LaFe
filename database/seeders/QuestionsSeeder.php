<?php

namespace Database\Seeders;

use App\Models\Questions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Questions::create(['question' => '¿Cuál es el apellido materno de tu madre?', 'id_padre' =>	10000]);
        Questions::create(['question' => '¿Cuál es el apellido paterno de tu padre?', 'id_padre' =>	10000]);
        Questions::create(['question' => '¿En qué ciudad nació tu madre?', 'id_padre' =>	10000]);

        Questions::create(['question' => '¿Cuál fue el nombre de tu primera mascota?', 'id_padre' =>	20000]);
        Questions::create(['question' => '¿En qué ciudad naciste?', 'id_padre' =>	20000]);
        Questions::create(['question' => '¿Qué país te gustaría visitar?', 'id_padre' =>	20000]);

        Questions::create(['question' => '¿Cuál es el segundo nombre de tu hermano menor?', 'id_padre' =>	30000]);
        Questions::create(['question' => '¿Cuál es el primer nombre de tu hermano mayor?', 'id_padre' =>	30000]);
    }
}
