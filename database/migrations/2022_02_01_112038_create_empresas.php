<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('departamento');
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->string('seudonimo')->nullable();
            $table->integer('id_person');
            $table->timestamps();

            $table->foreign('id_person')->references('id')->on('persons'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dependencias');
    }
};
