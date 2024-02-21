<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genero;

class GeneroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Genero::create(['id' => 1, 'valor' => "MASCULINO"]);
        Genero::create(['id' => 2, 'valor' => "FEMENINO"]);
    }
}
