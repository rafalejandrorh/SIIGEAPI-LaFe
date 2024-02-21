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
        Schema::create('trazas.api', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('mac');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_empresa')->nullable();
            $table->string('fecha_request');
            $table->string('action')->nullable();
            $table->string('response', 350);
            $table->string('request');
            $table->string('token', 350);
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_empresa')->references('id')->on('empresas'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trazas.api');
    }
};
