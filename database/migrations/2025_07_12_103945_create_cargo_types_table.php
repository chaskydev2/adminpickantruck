<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('cargo_types')) {
            Schema::create('cargo_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();
            });

            // Insertar tipos de carga por defecto
            DB::table('cargo_types')->insert([
                [
                    'name' => 'Carga General',
                    'description' => 'Carga general',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'Productos Perecederos',
                    'description' => 'Productos perecederos',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'Materiales Peligrosos',
                    'description' => 'Materiales peligrosos',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('cargo_types');
    }
};
