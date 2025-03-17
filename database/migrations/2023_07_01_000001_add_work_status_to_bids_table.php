<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkStatusToBidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // No necesitamos modificar físicamente la tabla,
        // pero mantenemos la migración para compatibilidad
        // y para seguir el historial de cambios en la base de datos
        
        // Si existiera una columna work_status, la implementaríamos así:
        /*
        Schema::table('bids', function (Blueprint $table) {
            $table->enum('work_status', ['pendiente', 'en_proceso', 'en_camino', 'entregado', 'completado', 'cancelado'])
                  ->default('pendiente');
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No necesitamos revertir nada
        
        /*
        Schema::table('bids', function (Blueprint $table) {
            $table->dropColumn('work_status');
        });
        */
    }
}
