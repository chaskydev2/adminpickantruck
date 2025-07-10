<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ofertas_ruta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tipo_camion');
            $table->string('origen', 255);
            $table->string('destino', 255);
            $table->dateTime('fecha_inicio');
            $table->decimal('capacidad', 10, 2);
            $table->decimal('precio_referencial', 10, 2);
            $table->timestamps();

            // Claves foráneas
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
                
            $table->foreign('tipo_camion')
                ->references('id')
                ->on('truck_types')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ofertas_ruta');
    }
};
