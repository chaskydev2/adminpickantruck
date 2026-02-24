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
        Schema::table('user_documents', function (Blueprint $table) {
            // Agregar la columna required_document_id
            $table->foreignId('required_document_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
            
            // Agregar columnas adicionales que el modelo espera
            $table->string('file_path')->nullable()->after('ruta_archivo');
            $table->string('status')->default('pendiente')->after('estado');
            $table->text('admin_notes')->nullable()->after('observaciones');
            $table->text('comments')->nullable()->after('admin_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_documents', function (Blueprint $table) {
            $table->dropForeign(['required_document_id']);
            $table->dropColumn(['required_document_id', 'file_path', 'status', 'admin_notes', 'comments']);
        });
    }
};
