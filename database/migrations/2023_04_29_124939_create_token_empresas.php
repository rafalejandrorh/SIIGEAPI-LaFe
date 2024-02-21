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
        Schema::create('token_empresas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_empresa')->unique();
            $table->string('token');
            $table->integer('duracion_token')->default(30);
            $table->boolean('estatus');
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('token_dependencias');
    }
};
