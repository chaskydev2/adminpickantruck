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
        if (!Schema::hasTable('user_details')) {
            Schema::create('user_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('phone', 20)->nullable();
                $table->text('address')->nullable();
                $table->timestamp('last_login_at')->nullable();
                $table->enum('role', ['carrier', 'forwarder', 'admin'])->default('forwarder');
                $table->timestamps();
                
                // Clave foránea con nombre específico para evitar conflictos
                $table->foreign('user_id', 'user_details_user_id_foreign')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
                
                // Índice único para user_id para asegurar relación 1:1
                $table->unique('user_id');
            });
            
            // Si la tabla users ya tiene datos, podríamos crear registros iniciales aquí
            // Pero eso debería manejarse con un seeder en lugar de en la migración
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Primero eliminamos la clave foránea si existe
        if (Schema::hasTable('user_details')) {
            Schema::table('user_details', function (Blueprint $table) {
                // Eliminar la restricción de clave foránea si existe
                $table->dropForeign('user_details_user_id_foreign');
                // Eliminar el índice único si existe
                $table->dropUnique('user_details_user_id_unique');
            });
            
            // Luego eliminamos la tabla
            Schema::dropIfExists('user_details');
        }
    }
};
