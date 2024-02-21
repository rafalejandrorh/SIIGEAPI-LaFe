<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Traza_Acciones;

class TrazaAccionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Traza_Acciones::create(['id' => 1, 'valor' => "Registro"]);
        Traza_Acciones::create(['id' => 2, 'valor' => "Actualización"]);
        Traza_Acciones::create(['id' => 3, 'valor' => "Eliminación"]);
        Traza_Acciones::create(['id' => 4, 'valor' => "Visualización"]);
        Traza_Acciones::create(['id' => 5, 'valor' => "Búsqueda"]);
        Traza_Acciones::create(['id' => 6, 'valor' => "PDF"]);
        Traza_Acciones::create(['id' => 7, 'valor' => "Restauración"]);
        Traza_Acciones::create(['id' => 8, 'valor' => "Descarga"]);
        Traza_Acciones::create(['id' => 9, 'valor' => "Papelera de Reciclaje"]);
        Traza_Acciones::create(['id' => 10, 'valor' => "Ingreso a Módulo"]);
    }
}
