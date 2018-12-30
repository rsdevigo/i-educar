<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreatePmieducarBloqueioLancamentoFaltasNotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            '
                SET default_with_oids = false;

                CREATE TABLE pmieducar.bloqueio_lancamento_faltas_notas (
                    cod_bloqueio integer DEFAULT nextval(\'public.bloqueio_lancamento_faltas_notas_seq\'::regclass) NOT NULL,
                    ano integer NOT NULL,
                    ref_cod_escola integer NOT NULL,
                    etapa integer NOT NULL,
                    data_inicio date NOT NULL,
                    data_fim date NOT NULL
                );
            '
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pmieducar.bloqueio_lancamento_faltas_notas');
    }
}
