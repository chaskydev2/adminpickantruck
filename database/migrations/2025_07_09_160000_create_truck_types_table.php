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
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Insertar tipos de camión por defecto
        DB::table('truck_types')->insert([
            ['name' => 'Camión Plataforma', 'description' => 'Camión con plataforma', 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Camión Caja', 'description' => 'Camión con caja cerrada', 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Camión Refrigerado', 'description' => 'Camión con refrigeración', 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('truck_types');
    }
};
