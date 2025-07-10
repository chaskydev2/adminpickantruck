<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('monto', 10, 2);
            $table->text('comentarios')->nullable();
            $table->string('estado')->default('pendiente');
            
            // Relación polimórfica
            $table->unsignedBigInteger('bideable_id');
            $table->string('bideable_type');
            
            // Índices para la relación polimórfica
            $table->index(['bideable_id', 'bideable_type']);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bids');
    }
};
