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
        Schema::table('trazas.api', function (Blueprint $table) {
            if (!Schema::hasColumn('trazas.api', 'time_execution')) {
                $table->float('time_execution', false)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trazas.api', function (Blueprint $table) {
            $table->dropColumn('time_execution');
        });
    }
};
