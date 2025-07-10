<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('truck_types', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('capacidad_kg', 10, 2);
            $table->decimal('largo', 8, 2);
            $table->decimal('ancho', 8, 2);
            $table->decimal('alto', 8, 2);
            $table->timestamps();
        });

        // Insertar tipos de camión por defecto
        DB::table('truck_types')->insert([
            ['nombre' => 'Camión Cerrado', 'descripcion' => 'Caja cerrada estándar', 'capacidad_kg' => 10000, 'largo' => 13.6, 'ancho' => 2.4, 'alto' => 2.7],
            ['nombre' => 'Torton', 'descripcion' => 'Caja cerrada grande', 'capacidad_kg' => 15000, 'largo' => 15.0, 'ancho' => 2.5, 'alto' => 2.8],
            ['nombre' => 'Rabón', 'descripcion' => 'Caja cerrada corta', 'capacidad_kg' => 7000, 'largo' => 8.5, 'ancho' => 2.4, 'alto' => 2.7],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('truck_types');
    }
};
