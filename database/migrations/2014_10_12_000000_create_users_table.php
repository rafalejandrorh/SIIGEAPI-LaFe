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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_person'); 
            $table->string('users')->unique();
            $table->string('password');
            $table->boolean('status');
            $table->timestamp('last_login');
            $table->boolean('password_status');
            $table->boolean('security_questions');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
