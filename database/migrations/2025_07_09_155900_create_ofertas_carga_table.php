<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ofertas_carga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tipo_carga');
            $table->string('origen');
            $table->string('destino');
            $table->decimal('peso', 10, 2);
            $table->dateTime('fecha_inicio');
            $table->decimal('presupuesto', 10, 2);
            $table->string('estado')->default('pendiente');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ofertas_carga');
    }
};
