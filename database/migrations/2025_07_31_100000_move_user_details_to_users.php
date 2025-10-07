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
        // 1. Agregar campos a la tabla users
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['carrier', 'forwarder', 'admin'])->default('forwarder')->after('email');
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
        });

        // 2. Copiar datos de user_details a users
        if (Schema::hasTable('user_details')) {
            $details = \DB::table('user_details')->get();
            
            foreach ($details as $detail) {
                \DB::table('users')
                    ->where('id', $detail->user_id)
                    ->update([
                        'role' => $detail->role,
                        'last_login_at' => $detail->last_login_at
                    ]);
            }
        }

        // 3. Eliminar la tabla user_details
        Schema::dropIfExists('user_details');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Recrear la tabla user_details
        if (!Schema::hasTable('user_details')) {
            Schema::create('user_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('phone', 20)->nullable();
                $table->text('address')->nullable();
                $table->timestamp('last_login_at')->nullable();
                $table->enum('role', ['carrier', 'forwarder', 'admin'])->default('forwarder');
                $table->timestamps();
                
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            });

            // 2. Copiar datos de users a user_details
            $users = \DB::table('users')->get();
            
            foreach ($users as $user) {
                if (isset($user->role) || isset($user->last_login_at)) {
                    \DB::table('user_details')->insert([
                        'user_id' => $user->id,
                        'role' => $user->role ?? 'forwarder',
                        'last_login_at' => $user->last_login_at,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // 3. Eliminar las columnas agregadas
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'last_login_at']);
        });
    }
};
