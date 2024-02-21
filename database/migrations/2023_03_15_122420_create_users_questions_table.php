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
        Schema::create('users_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_users');
            $table->unsignedInteger('id_questions');
            $table->string('response');
            $table->unsignedInteger('id_padre');
            $table->timestamps();

            $table->foreign('id_users')->references('id')->on('users'); 
            $table->foreign('id_questions')->references('id')->on('nomenclador.questions'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_questions');
    }
};
