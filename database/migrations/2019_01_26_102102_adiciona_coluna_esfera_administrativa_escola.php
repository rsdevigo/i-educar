<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdicionaColunaEsferaAdministrativaEscola extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pmieducar.escola', function (Blueprint $table) {
            $table->integer('esfera_administrativa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pmieducar.escola', function (Blueprint $table) {
            $table->dropColumn('esfera_administrativa');
        });
    }
}
