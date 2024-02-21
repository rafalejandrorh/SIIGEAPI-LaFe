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
        Schema::create('historial_sesion', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_user');
            $table->dateTime('login')->nullable();
            $table->dateTime('logout')->nullable();
            $table->integer('tipo_logout')->nullable();
            $table->string('MAC');
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
        Schema::dropIfExists('historial_sesion');
    }
};
