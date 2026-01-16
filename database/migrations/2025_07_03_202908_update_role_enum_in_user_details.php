<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateRoleEnumInUserDetails extends Migration
{
    /**
     * Ejecutar las migraciones.
     */
    public function up()
    {
        if (Schema::hasTable('user_details')) {
            // Verificar si la columna 'role' existe
            if (Schema::hasColumn('user_details', 'role')) {
                // Actualizar los valores existentes de 'shipper' a 'forwarder' si existen
                DB::table('user_details')
                    ->where('role', 'shipper')
                    ->update(['role' => 'forwarder']);
                
                // Usar DB::statement solo si es necesario para modificar la columna
                // Primero verificar el motor de base de datos
                $driver = DB::getDriverName();
                
                if ($driver === 'mysql') {
                    // Para MySQL
                    DB::statement("ALTER TABLE user_details MODIFY COLUMN `role` ENUM('carrier', 'forwarder', 'admin') NOT NULL DEFAULT 'forwarder'");
                } elseif ($driver === 'pgsql') {
                    // Para PostgreSQL
                    DB::statement("ALTER TABLE user_details ALTER COLUMN role TYPE VARCHAR(20)");
                    DB::statement("ALTER TABLE user_details ADD CONSTRAINT role_check CHECK (role IN ('carrier', 'forwarder', 'admin'))");
                    DB::statement("ALTER TABLE user_details ALTER COLUMN role SET DEFAULT 'forwarder'");
                }
            }
        }
    }

    /**
     * Revertir las migraciones.
     */
    public function down()
    {
        if (Schema::hasTable('user_details') && Schema::hasColumn('user_details', 'role')) {
            // Revertir los cambios: actualizar 'forwarder' de vuelta a 'shipper'
            DB::table('user_details')
                ->where('role', 'forwarder')
                ->update(['role' => 'shipper']);
            
            // Revertir el tipo de columna al original
            $driver = DB::getDriverName();
            
            if ($driver === 'mysql') {
                // Para MySQL
                DB::statement("ALTER TABLE user_details MODIFY COLUMN `role` ENUM('carrier', 'shipper', 'admin') NOT NULL DEFAULT 'shipper'");
            } elseif ($driver === 'pgsql') {
                // Para PostgreSQL
                DB::statement("ALTER TABLE user_details DROP CONSTRAINT IF EXISTS role_check");
                DB::statement("ALTER TABLE user_details ALTER COLUMN role TYPE VARCHAR(20)");
                DB::statement("ALTER TABLE user_details ADD CONSTRAINT role_check CHECK (role IN ('carrier', 'shipper', 'admin'))");
                DB::statement("ALTER TABLE user_details ALTER COLUMN role SET DEFAULT 'shipper'");
            }
        }
    }
}
