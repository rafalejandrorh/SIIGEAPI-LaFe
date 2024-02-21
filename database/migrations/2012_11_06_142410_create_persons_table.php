<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->char('letra_cedula', 1)->nullable();
            $table->integer('cedula')->unique()->nullable();
            $table->string('primer_nombre', 50);
            $table->string('segundo_nombre', 50)->nullable();
            $table->string('primer_apellido', 50);
            $table->string('segundo_apellido', 50)->nullable();
            $table->unsignedInteger('id_genero')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->unsignedInteger('id_estado_nacimiento')->nullable();
            $table->unsignedInteger('id_municipio_nacimiento')->nullable();
            $table->unsignedInteger('id_pais_nacimiento')->nullable();

            $table->foreign('id_genero')->references('id')->on('nomenclador.genero'); 
            $table->foreign('id_estado_nacimiento')->references('id')->on('nomenclador.geografia');
            $table->foreign('id_municipio_nacimiento')->references('id')->on('nomenclador.geografia');
            $table->foreign('id_pais_nacimiento')->references('id')->on('nomenclador.geografia');
             
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persons');
    }
}
