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
        Schema::table('users', function (Blueprint $table) {
            // Verificar si la columna ya existe para evitar errores
            if (!Schema::hasColumn('users', 'estado')) {
                $table->enum('estado', ['Bloqueado', 'Activo'])->nullable()->after('email');
                
                // Establecer el valor por defecto para usuarios existentes
                \DB::table('users')->whereNull('estado')->update(['estado' => 'Activo']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
