<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nomenclador.geografia', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('valor', 300);
            $table->unsignedInteger('id_padre');
            $table->unsignedInteger('id_hijo');
             
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
        Schema::dropIfExists('nomenclador.geografia');
    }
};
